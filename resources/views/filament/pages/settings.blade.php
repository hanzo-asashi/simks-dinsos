<x-filament-panels::page>
    <div class="space-y-5">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6">
            <x-filament::card>
                <div class="space-y-6">
                    <div class="flex space-x-3 items-center rtl:space-x-reverse">
                        <div class="flex-none h-8 w-8 rounded-full bg-black/50 dark:bg-black/50 text-slate-300 flex flex-col items-center
                                        justify-center text-lg">
                            <x-filament::icon
                                alias="panels::topbar.global-search.field"
                                icon="heroicon-o-building-office-2"
                                class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            />
                        </div>
                        <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                            System Settings
                        </div>
                    </div>
                    <div class="text-slate-600 dark:text-slate-300 text-sm">
                        Set up your app settings
                    </div>
                    <x-filament::link :href="'general-setting'" icon="heroicon-m-arrow-right" icon-position="after" size="sm">
                        Change Settings
                    </x-filament::link>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="space-y-6">
                    <div class="flex space-x-3 items-center rtl:space-x-reverse">
                        <div class="flex-none h-8 w-8 rounded-full bg-black/50 dark:bg-black/50 text-slate-300 flex flex-col items-center
                                        justify-center text-lg">
                            <x-filament::icon
                                icon="heroicon-o-user-circle"
                                class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            />
                        </div>
                        <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                            User Management
                        </div>
                    </div>
                    <div class="text-slate-600 dark:text-slate-300 text-sm">
                        Manage your user, add, edit, delete, and more
                    </div>
                    <x-filament::link :href="'user'" icon="heroicon-m-arrow-right" icon-position="after" size="sm">
                        Change Settings
                    </x-filament::link>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="space-y-6">
                    <div class="flex space-x-3 items-center rtl:space-x-reverse">
                        <div class="flex-none h-8 w-8 rounded-full bg-black/50 dark:bg-black/50 text-slate-300 flex flex-col items-center
                                        justify-center text-lg">
                            <x-filament::icon
                                icon="heroicon-o-lock-closed"
                                class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            />
                        </div>
                        <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                            Role Management
                        </div>
                    </div>
                    <div class="text-slate-600 dark:text-slate-300 text-sm">
                        Manage your user role, add, edit, delete, and more
                    </div>
                    <x-filament::link :href="'roles'" icon="heroicon-m-arrow-right" icon-position="after" size="sm">
                        Change Settings
                    </x-filament::link>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="space-y-6">
                    <div class="flex space-x-3 items-center rtl:space-x-reverse">
                        <div class="flex-none h-8 w-8 rounded-full bg-black/50 dark:bg-black/50 text-slate-300 flex flex-col items-center
                                        justify-center text-lg">
                            <x-filament::icon
                                icon="heroicon-o-user"
                                class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            />
                        </div>
                        <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                            Profile Setting
                        </div>
                    </div>
                    <div class="text-slate-600 dark:text-slate-300 text-sm">
                        Set up your profile, add your profile photo, and more
                    </div>
                    <x-filament::link :href="'my-profile'" icon="heroicon-m-arrow-right" icon-position="after" size="sm">
                        Change Settings
                    </x-filament::link>
                </div>
            </x-filament::card>
        </div>
    </div>
</x-filament-panels::page>
