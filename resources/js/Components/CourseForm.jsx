import React, { useState } from 'react';
import { useForm } from '@inertiajs/inertia-react'
import Select2 from '@/Components/Select2/Select2'

export default function CourseForm({ action = 'store', course = {}, defaultSources }) {

    const { data, setData, get, post, patch, processing, errors } = useForm({
        name: course?.name ?? '',
        sourceId: course?.source_id ?? 0, // default will be {}
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

    /** TODO: Integrate with real endpoint */
    const loadSources = (inputValue, callback) => {
        setTimeout(() => {
            callback(defaultOptions)
        }, 1000)
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
                </div>
                <div className='w-1/2 p-4'>
                    <label className='font-bold block' htmlFor="course_source">Source</label>
                    <Select2
                        value={data.sourceId}
                        defaultOptions={defaultOptions}
                        loadOptions={loadSources}
                        onChange={value => setData('sourceId', value)}
                    />
                </div>
            </div>

            <div className='p-4'>
                <label className='font-bold block' htmlFor="course_url">URL</label>
                <input
                    value={data.url} 
                    onChange={e => setData('url', e.target.value)}
                    id='course_url'
                />
            </div>

            <div className='p-4'>
                <label className='font-bold' htmlFor="course_description">Description</label>
                <textarea
                    value={data.description}
                    onChange={e => setData('description', e.target.value)}
                    id="course_description"
                    rows="5"
                />
            </div>
            
            <div className='p-4 ml-auto text-right'>
                <button disabled={processing} className='bg-gray-100 hover:bg-gray-200 px-6 border border-gray-100 py-2 font-semibold rounded ml-2' type="submit">Submit</button>
            </div>
        </form>
    );
}