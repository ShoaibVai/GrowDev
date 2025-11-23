@props(['task'])

<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $task->title }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->priority === 'Critical' ? 'bg-red-100 text-red-800' : ($task->priority === 'High' ? 'bg-orange-100 text-orange-800' : ($task->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">{{ $task->priority }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($task->due_date)->format('M d') ?? '-' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->status }}</td>
</tr>
