@extends('layouts.app')

@section('vendor-scripts')
    @vite(['resources/assets/js/chatbot.js'])
@endsection

@section('content')

    <x-common.page-breadcrumb :pageTitle="[['name' => 'AI Chat', 'link'=> '#']]" />

    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6"> {{--here use display grid for the sidebar--}}

            <!-- Sidebar -->
            <aside class="lg:col-span-3">
                <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 h-[80vh] flex flex-col">

                    <!-- Header -->
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                                    AI Assistant
                                </h2>
                                <p class="text-sm text-slate-500 mt-1">
                                    Smart conversations
                                </p>
                            </div>

                            <button
                                class="w-10 h-10 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="p-4">
                        <div class="relative">
                            <input
                                type="text"
                                placeholder="Search conversations..."
                                class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 pl-11 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            >

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 absolute left-4 top-3.5 text-slate-400"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Conversation List -->
                    <div class="flex-1 overflow-y-auto px-3 pb-4 space-y-2">

                        <button
                            class="w-full text-left p-4 rounded-2xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition">
                            <p class="font-medium text-slate-800 dark:text-white">
                                Product Discussion
                            </p>
                            <p class="text-xs text-slate-500 mt-1 truncate">
                                Generate product descriptions...
                            </p>
                        </button>

                        <button
                            class="w-full text-left p-4 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <p class="font-medium text-slate-700 dark:text-slate-200">
                                Marketing Ideas
                            </p>
                            <p class="text-xs text-slate-500 mt-1 truncate">
                                Create social media campaign...
                            </p>
                        </button>

                        <button
                            class="w-full text-left p-4 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <p class="font-medium text-slate-700 dark:text-slate-200">
                                Customer Support
                            </p>
                            <p class="text-xs text-slate-500 mt-1 truncate">
                                Reply to customer queries...
                            </p>
                        </button>

                    </div>
                </div>
            </aside>

            <!-- Chat Area -->
            <section class="lg:col-span-9">
                <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 h-[80vh] flex flex-col overflow-hidden">

                    <div
                        class="[scrollbar-width:none] flex-1 overflow-y-auto px-6 py-6 space-y-6 bg-linear-to-b from-slate-50 to-white dark:from-slate-950 dark:to-slate-900" id="userMessageBox">

                    </div>

                    <form method="POST" action="{{route('admin.ai-chat.continue')}}" id="form">
                        @csrf
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">


                            <div
                                class="flex items-stretch gap-3 bg-slate-100 dark:bg-slate-800 rounded-3xl p-3 border border-slate-200 dark:border-slate-700">

                            <textarea
                                rows="1"
                                name="prompt"
                                id="prompt"
                                placeholder="Type your message..."
                                class="outline-none flex-1 bg-transparent resize-none border-0 focus:ring-0 text-slate-700 dark:text-white placeholder:text-slate-400"></textarea>

                                <button
                                    class="w-12 h-12 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow-lg transition" type="submit">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M14.752 11.168l-9.193-5.106A1 1 0 004 6.94v10.12a1 1 0 001.559.832l9.193-5.106a1 1 0 000-1.664z"/>
                                    </svg>

                                </button>
                            </div>
                    </div>
                    </form>

                </div>
            </section>

        </div>
    </div>

@endsection
