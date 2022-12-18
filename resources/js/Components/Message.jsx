export default function Message({ message, error }) {
    return message && 
        <p className={`py-2 px-3 mb-3 rounded ${error ? 'bg-red-100' : 'bg-green-100'}`}>{message}</p>
}