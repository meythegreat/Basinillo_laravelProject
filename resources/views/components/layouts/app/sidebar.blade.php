<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen flex bg-zinc-50 text-gray-900 dark:bg-zinc-950 dark:text-gray-100">
    <flux:sidebar
        sticky
        stashable
        class="min-h-screen w-64 lg:w-72 shrink-0 flex-none
            border-e border-zinc-200 bg-zinc-50/95 backdrop-blur
            dark:border-zinc-800 dark:bg-zinc-900/95"
    >
            {{-- Top: logo + app name + close on mobile --}}
            <div class="flex items-center justify-between gap-2 mb-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2" wire:navigate>
                    <x-app-logo class="h-8 w-8" />
                    <div class="hidden sm:flex flex-col leading-tight">
                        <span class="text-sm font-semibold">Music Library</span>
                        <span class="text-[11px] text-zinc-500 dark:text-zinc-400">Dashboard & Genres</span>
                    </div>
                </a>

                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            </div>

            {{-- Nav --}}
            <flux:navlist
                variant="outline"
                class="mt-2 rounded-2xl border border-zinc-200/70 bg-white/80 dark:border-zinc-800 dark:bg-zinc-900/80 shadow-sm"
            >
                <flux:navlist.group
                    :heading="__('Library')"
                    class="grid gap-0"
                >
                    <flux:navlist.item
                        icon="musical-note"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        class="rounded-xl px-3 py-2 text-sm data-[current=true]:bg-indigo-50 data-[current=true]:text-indigo-700 dark:data-[current=true]:bg-indigo-500/10 dark:data-[current=true]:text-indigo-300"
                        wire:navigate
                    >
                        {{ __('Music Dashboard') }}
                    </flux:navlist.item>

                    <flux:navlist.item
                        icon="tag"
                        :href="route('genres.index')"
                        :current="request()->routeIs('genres.*')"
                        class="rounded-xl px-3 py-2 text-sm data-[current=true]:bg-indigo-50 data-[current=true]:text-indigo-700 dark:data-[current=true]:bg-indigo-500/10 dark:data-[current=true]:text-indigo-300"
                        wire:navigate
                    >
                        {{ __('Genres') }}
                    </flux:navlist.item>
                </flux:navlist.group>

                    <flux:navlist.item
                icon="trash"
                :href="route('songs.trash')"
                :current="request()->routeIs('songs.trash')"
                class="rounded-xl px-3 py-2 text-sm data-[current=true]:bg-indigo-50 data-[current=true]:text-indigo-700 dark:data-[current=true]:bg-indigo-500/10 dark:data-[current=true]:text-indigo-300"
                wire:navigate
            >
                {{ __('Trash') }}
            </flux:navlist.item>
            </flux:navlist>

            <flux:spacer />

            {{-- Bottom: user menu (desktop) --}}
            <div class="hidden lg:block border-t border-zinc-200/70 pt-3 mt-2 dark:border-zinc-800">
                <flux:dropdown class="w-full" position="bottom" align="start">
                    <flux:profile
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        icon:trailing="chevrons-up-down"
                        class="w-full rounded-xl px-2 py-1.5 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    />

                    <flux:menu class="w-[240px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-2 py-2 text-start text-sm">
                                    <span class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item
                                :href="route('settings.profile')"
                                icon="cog"
                                wire:navigate
                            >
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full"
                            >
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:sidebar>

        {{-- Mobile header --}}
        <flux:header class="lg:hidden border-b border-zinc-200/70 bg-white/90 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/90">
            <div class="flex w-full items-center gap-2 px-3">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

                <div class="flex items-center gap-2">
                    <x-app-logo class="h-7 w-7" />
                    <span class="text-sm font-semibold">Music Library</span>
                </div>

                <flux:spacer />

                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />

                    <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-2 py-2 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item
                                :href="route('settings.profile')"
                                icon="cog"
                                wire:navigate
                            >
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full"
                            >
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
    <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
</html>