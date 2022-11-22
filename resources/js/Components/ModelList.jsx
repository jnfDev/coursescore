import { Link } from '@inertiajs/inertia-react';

export default function ModelList({ items }) {

    return (
        <table className='w-full text-left table-auto'>
            <thead>
                <tr className='bg-gray-100'>
                    <th className='px-5 py-3'>ID</th>
                    <th className='px-5 py-3'>Name</th>
                    <th className='px-5 py-3'>Action</th>
                </tr>
            </thead>
            <tbody>
                {items.map((item, i) => (
                    <tr className={0 !== i ? 'border-t border-gray-200' : ''} key={item.id}>
                        <td className='px-5 py-3'>
                            {item.id}
                        </td>
                        <td className='px-5 py-3'>
                            {item.name}
                        </td>
                        <td className='px-5 py-3'>
                            <Link className='text-sm hover:bg-gray-200 px-4 py-2 font-semibold hover:font-bold rounded mr-2' method='delete' as='button' href={route('courses.destroy', item.id)}>
                                🗑️ Delete
                            </Link>

                            <Link className='text-sm hover:bg-gray-200 px-4 py-2 font-semibold hover:font-bold rounded' href="#">
                                📝 Edit
                            </Link>
                        </td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
}