import React from 'react';
import { useForm } from '@inertiajs/inertia-react'
import Select2 from '@/Components/Select2/Select2'
import InputError from '@/Components/InputError';

export default function SourceForm({ action = 'store', source = {}, channels = [] }) {

    const { data, setData, post, patch, processing, errors } = useForm({
        name: source?.name ?? '',
        channel: source?.channel ?? '',
        user_id: source?.userId ?? 1,
        description: source?.description ?? ''
    })

    console.log(errors);

    const _channels = channels.map(channel => ({ label: channel, value: channel }))

    const onSubmit = (e) => {
        e.preventDefault()

        if ('store' === action) {
            post(route('sources.store'))
            return;
        }

        if ('update' === action) {
            patch(route('sources.update', source.id))
        }
    }

    return (
        <form onSubmit={onSubmit}>

            <div className='flex'>
                <div className='w-1/2 p-4'>
                    <label className='font-bold block' htmlFor="source_name">Name </label>
                    <input
                        value={data.name} 
                        onChange={e => setData('name', e.target.value)}
                        id='source_name'
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className='w-1/2 p-4'>
                    <label className='font-bold block' htmlFor="source_channel">Channel</label>
                    <Select2
                        value={data.channel}
                        defaultOptions={_channels}
                        onChange={opt => setData('channel', opt.value)}
                        id="source_channel"
                    />
                    <InputError message={errors.channel} className="mt-2" />
                </div>
            </div>
            <div className='p-4'>
                <label className='font-bold' htmlFor="source_description">Description</label>
                <textarea
                    value={data.description}
                    onChange={e => setData('description', e.target.value)}
                    rows="5"
                    id='source_description'
                />
                <InputError message={errors.description} className="mt-2" />
            </div>

            <div className='p-4 ml-auto text-right'>
                <button disabled={processing} className='bg-gray-100 hover:bg-gray-200 px-6 border border-gray-100 py-2 font-semibold rounded ml-2' type="submit">Submit</button>
            </div>
        </form>
    )
}