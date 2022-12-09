import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/inertia-react';
import ResourceList from '@/Components/ResourceList';

export default function Index({ auth, errors, sources }) {
    return (
        <AuthenticatedLayout
            auth={auth}
            errors={errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Courses</h2>}
        >
            <Head title="Courses Listing" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200 flex">

                            <div className='w-1/4'>
                                <h1 className='text-2xl font-black mb-3'>
                                    Sources
                                    <Link className='text-sm bg-gray-100 hover:bg-gray-200 px-2 font-semibold hover:font-bold rounded ml-2' href={route('sources.create')}>Add ➕</Link>
                                </h1>
                                <small>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus maiores excepturi nostrum unde, numquam iure?</small>
                            </div>

                            <div className='w-3/4 ml-6'>
                                <ResourceList resource='sources' items={sources} />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}