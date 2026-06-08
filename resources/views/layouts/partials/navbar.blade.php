<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-2">

    <div class="flex items-center justify-between h-16">

        <!-- Logo -->
        <div class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="FAYHOS"
                class="h-10 w-10 rounded-lg">

            <div>
                <div class="font-bold text-green-700">
                    FAYHOS
                </div>

                <div class="text-xs text-gray-500">
                    Electronic Hospital Information Management System 
                </div>
            </div>

        </div>

        <!-- Navigation -->
        <div class="hidden md:flex items-center gap-6">

            <a href="{{ route('dashboard') }}"
               class="text-sm font-medium hover:text-green-600">
                Dashboard
            </a>

            <a href="{{ route('lab.requests.index') }}"
               class="text-sm font-medium hover:text-green-600">
                Requests
            </a>

            <a href="{{ route('lab.result') }}"
               class="text-sm font-medium hover:text-green-600">
                Result Entry
            </a>

            <a href="{{ route('lab.investigations.index') }}"
               class="text-sm font-medium hover:text-green-600">
                Investigations
            </a>

            <a href="#"
               class="text-sm font-medium hover:text-green-600">
                Completed Results
            </a>

        </div>

        <!-- User -->
        <div class="flex items-center gap-3">

            <flux:dropdown>

                <flux:button variant="ghost">

                    {{ auth()->user()->name }}

                </flux:button>

                <flux:menu>

                    <flux:menu.item
                        href="{{ route('profile.edit') }}">

                        Profile

                    </flux:menu.item>

                    <flux:menu.separator />

                    <flux:menu.item>

                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="w-full text-left">
                                Logout
                            </button>

                        </form>

                    </flux:menu.item>

                </flux:menu>

            </flux:dropdown>

        </div>

    </div>

</div>


</nav>
