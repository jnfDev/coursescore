import React from 'react';
import { useForm, usePage } from '@inertiajs/inertia-react'
import Select2 from '@/Components/Select2/Select2'
import InputError from '@/Components/InputError';

export default function CourseForm({ action = 'store', course = {}, defaultSources }) {
    const { auth: { user } } = usePage().props
    const { data, setData, post, patch, processing, errors } = useForm({
        name: course?.name ?? undefined,
        source_id: course?.source_id ?? undefined,
        user_id: course?.user_id ?? user.id,
        url: course?.url ?? '',
        description: course?.description ?? ''
    })

    const onSubmit = (e) => {
        e.preventDefault()

        if ('store' === action) {
            post(route('courses.store'))
            return;
        }

        if ('update' === action) {
            patch(route('courses.update', course.id))
        }
    }

    const defaultOptions = defaultSources.map((source) => ({ label: source.name, value: source.id }))

    const loadSources = async (search, callback) => {
        return fetch(`/admin/sources/search/${search}`)
            .then(response => response.json())
            .then(data => {
                const newOptions = data.map(d => ({ value: d.id, label: d.name }))
                callback(newOptions)
            })
    }

    return (
        <form onSubmit={onSubmit}>
            <div className='flex'>
                <div className='w-1/2 p-4'>
                    <label className='font-bold block' htmlFor="course_name">Name </label>
                    <input 
                        value={data.name} 
                        onChange={e => setData('name', e.target.value)}
                        id='course_name'
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>
                <div className='w-1/2 p-4'>
                    <label className='font-bold block' htmlFor="course_source">Source</label>
                    <Select2
                        value={data.source_id}
                        defaultOptions={defaultOptions}
                        loadOptions={loadSources}
                        onChange={opt => setData('source_id', opt.value)}
                    />
                    <InputError message={errors.source_id} className="mt-2" />
                </div>
            </div>

            <div className='p-4'>
                <label className='font-bold block' htmlFor="course_url">URL</label>
                <input
                    value={data.url} 
                    onChange={e => setData('url', e.target.value)}
                    id='course_url'
                />
                <InputError message={errors.url} className="mt-2" />
            </div>

            <div className='p-4'>
                <label className='font-bold' htmlFor="course_description">Description</label>
                <textarea
                    value={data.description}
                    onChange={e => setData('description', e.target.value)}
                    id="course_description"
                    rows="5"
                />
                <InputError message={errors.description} className="mt-2" />
            </div>
            
            <div className='p-4 ml-auto text-right'>
                <button disabled={processing} className='bg-gray-100 hover:bg-gray-200 px-6 border border-gray-100 py-2 font-semibold rounded ml-2' type="submit">Submit</button>
            </div>
        </form>
    );
}