<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('All Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-hidden overflow-x-auto bg-white">
                        <div class="min-w-full align-middle">
                            @can(\App\Enums\PermissionEnum::CreateTask)
                                <a href="{{ route('tasks.create') }}" class="underline">Add new task</a>
                                <br />
                                <br />
                            @endcan

                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50 px-6 py-3 text-left">
                                            <span
                                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                                            >
                                                Name
                                            </span>
                                        </th>
                                        <th class="bg-gray-50 px-6 py-3 text-left">
                                            <span
                                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                                            >
                                                Assigned To
                                            </span>
                                        </th>
                                        <th class="bg-gray-50 px-6 py-3 text-left">
                                            <span
                                                class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                                            >
                                                Patient
                                            </span>
                                        </th>
                                        <th class="bg-gray-50 px-6 py-3 text-left"></th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-solid divide-gray-200 bg-white">
                                    @foreach ($tasks as $task)
                                        <tr class="bg-white">
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                {{ $task->name }}
                                            </td>
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                {{ $task->assignee->name }}
                                            </td>
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                {{ $task->patient->name }}
                                            </td>
                                            <td class="whitespace-no-wrap px-6 py-4 text-sm leading-5 text-gray-900">
                                                @can(\App\Enums\PermissionEnum::EditTask)
                                                    <a href="{{ route('tasks.edit', $task) }}" class="underline">
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can(\App\Enums\PermissionEnum::DeleteTask)
                                                    |
                                                    <form
                                                        action="{{ route('tasks.destroy', $task) }}"
                                                        method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Are you sure?')"
                                                    >
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="text-red-600 underline">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
