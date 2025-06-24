<aside id="drawer-navigation"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform bg-white border-r border-gray-200 
           -translate-x-full md:translate-x-0 dark:bg-gray-800 dark:border-gray-700">

    <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
        <ul class="space-y-3">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('kabid-dukbis.dashboard') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('kabid-dukbis.dashboard') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            {{-- Data Petugas Security --}}
            <li>
                <a href="{{ route('kabid-dukbis.data-petugas-security') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('kabid-dukbis.data-petugas-security') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         fill="currentColor" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512h388.6c1.8 0 3.5-.2 5.3-.5c-76.3-55.1-99.8-141-103.1-200.2c-16.1-4.8-33.1-7.3-50.7-7.3H178.3zm308.8-78.3l-120 48C358 277.4 352 286.2 352 296c0 63.3 25.9 168.8 134.8 214.2c5.9 2.5 12.6 2.5 18.5 0C614.1 464.8 640 359.3 640 296c0-9.8-6-18.6-15.1-22.3l-120-48c-5.7-2.3-12.1-2.3-17.8 0zM591.4 312c-3.9 50.7-27.2 116.7-95.4 149.7V273.8l95.4 38.2z"/>
                    </svg>
                    <span class="ml-3">Data Petugas Security</span>
                </a>
            </li>

            {{-- Data Lokasi Patroli --}}
            <li>
                <a href="{{ route('kabid-dukbis.data-lokasi-patroli') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('kabid-dukbis.data-lokasi-patroli') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor">
                        <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"></path>
                    </svg>
                    <span class="ml-3">Data Lokasi Patroli</span>
                </a>
            </li>

            {{-- Laporan Patroli --}}
            <li>
                <a href="{{ route('kabid-dukbis.laporan-patroli') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('kabid-dukbis.laporan-patroli') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         fill="currentColor" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 64C0 28.7 28.7 0 64 0h160v128c0 17.7 14.3 32 32 32h128v47l-92.8 37.1c-21.3 8.5-35.2 29.1-35.2 52 0 56.6 18.9 148 94.2 208.3-9 4.8-19.3 7.6-30.2 7.6H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0l128 128zm39.1 97.7c5.7-2.3 12.1-2.3 17.8 0l120 48c9.1 3.6 15.1 12.4 15.1 22.3 0 63.3-25.9 168.8-134.8 214.2-5.9 2.5-12.6 2.5-18.5 0C313.9 464.8 288 359.3 288 296c0-9.8 6-18.6 15.1-22.3l120-48zm104.3 50.3L432 273.8v187.8c68.2-33 91.5-99 95.4-149.7z"/>
                    </svg>
                    <span class="ml-3">Laporan Patroli</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
