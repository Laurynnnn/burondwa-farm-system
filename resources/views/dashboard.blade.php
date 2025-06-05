<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-forest-green leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-dark-green">
                <div class="p-6 text-charcoal">
                    {{ __('Welcome to Burondwa Farm System!') }}
                </div>
            </div>
            
            <div class="mt-6">
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>Total Inventory</h3>
                        <p class="text-2xl font-bold text-dark-green">0</p>
                    </div>
                    <div class="stat-card">
                        <h3>Active Staff</h3>
                        <p class="text-2xl font-bold text-dark-green">0</p>
                    </div>
                    <div class="stat-card">
                        <h3>Tasks Today</h3>
                        <p class="text-2xl font-bold text-dark-green">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
