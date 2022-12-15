export default function Message({ message }) {
    return message && <p className='bg-green-100 py-2 px-3 mb-3 rounded'>{message}</p>
}