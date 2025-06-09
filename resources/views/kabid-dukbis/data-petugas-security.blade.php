<x-app-layout :title="'Data Petugas Security'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Petugas Security</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                <form action="{{ route('kabid-dukbis.data-petugas-security') }}" method="GET"
                    class="w-full md:w-1/2 flex flex-col md:flex-row items-start md:items-center gap-2">
                    <div class="relative flex w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari petugas..." autocomplete="off"
                            class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg">
                            Cari
                        </button>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('kabid-dukbis.data-petugas-security') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg">
                            Kembali
                        </a>
                    @endif
                </form>

            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Nomor Induk</th>
                        <th class="px-4 py-3">No Telepon</th>
                        <th class="px-4 py-3">Alamat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>
                            <td class="px-4 py-2">{{ $user->nama }}</td>
                            <td class="px-4 py-2">
                                @if ($user->foto && file_exists(public_path('storage/' . $user->foto)))
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto"
                                        class="w-16 max-w-full h-auto object-cover rounded-full mx-auto border shadow">
                                @else
                                    <div
                                        class="w-16 h-16 bg-gray-100 flex items-center justify-center rounded-full mx-auto border shadow">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->nomor_induk ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $user->no_telepon ?? '-' }}</td>
                            <td class="px-4 py-2 text-left">{{ $user->alamat ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                Tidak ada data petugas tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center bg-white">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari
                {{ $users->total() }} data
            </div>
            <div>
                {{ $users->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
