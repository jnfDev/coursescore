import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';

export default function Error(props) {
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Unexpected Error</h2>}
        >
            <Head title="Error" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-red-200 border-b border-gray-200">
                            <h1>An unexpected error occurred. Some tips: </h1>
                            <ul>
                                <li>- Be calm.</li>
                                <li>- Throw your pc thru the window.</li>
                                <li>- Get yourself a rest.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
