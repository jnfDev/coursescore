import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import CourseForm from '@/Components/CourseForm';
import { Head, Link } from '@inertiajs/inertia-react';

export default function Create({ auth, errors, defaultSources }) {

    return (
        <AuthenticatedLayout
            auth={auth}
            errors={errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Courses</h2>}
        >
            <Head title="Courses Create" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200 flex">
                            <div className='w-1/4'>
                                <h1 className='text-2xl font-black mb-3'>
                                    Courses 
                                    <Link className='text-sm bg-gray-100 hover:bg-gray-200 px-2 font-semibold hover:font-bold rounded ml-2' href={route('courses.index')}>Back ⏎</Link>
                                </h1>
                                <small>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus maiores excepturi nostrum unde, numquam iure?</small>
                            </div>

                            <div className='w-3/4 ml-6'>
                                <CourseForm  defaultSources={defaultSources} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </AuthenticatedLayout>
    );
}