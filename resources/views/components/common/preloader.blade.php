<div
{{--  x-show="loaded"--}}
{{--  x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 350)})"--}}
{{--  class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"--}}
{{-->--}}
{{--  <div--}}
{{--    class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"--}}
{{--  ></div>--}}
{{--</div>--}}



<!-- resources/views/partials/preloader.blade.php -->
<div
    x-show="loaded"
    x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 350)})"
    class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
>
        <div class="absolute inset-0">

            <div class="absolute inset-0 opacity-[0.07]
                    bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)]
                    bg-size-[40px_40px]"></div>

            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-teal-500/20 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl animate-pulse [animation-delay:700ms]"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-md h-112 rounded-full bg-teal-400/10 blur-3xl"></div>

            <!-- Floating drifting pills, scattered -->
            <span class="absolute top-[15%] left-[12%] w-6 h-3 rounded-full bg-linear-to-r from-emerald-400/40 to-white/30 rotate-30 animate-bounce [animation-duration:4s]"></span>
            <span class="absolute bottom-[18%] left-[20%] w-5 h-2.5 rounded-full bg-linear-to-r from-teal-400/40 to-white/30 -rotate-20 animate-bounce [animation-duration:5s] [animation-delay:300ms]"></span>
            <span class="absolute top-[22%] right-[15%] w-5 h-2.5 rounded-full bg-linear-to-r from-emerald-400/40 to-white/30 rotate-60 animate-bounce [animation-duration:4.5s] [animation-delay:600ms]"></span>
            <span class="absolute bottom-[25%] right-[12%] w-6 h-3 rounded-full bg-linear-to-r from-teal-400/40 to-white/30 -rotate-45 animate-bounce [animation-duration:5.5s] [animation-delay:200ms]"></span>

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,rgba(15,23,42,0.6)_100%)]"></div>
        </div>

        <div class="relative flex flex-col items-center gap-6">

            <div class="relative w-32 h-32">

                <span class="absolute inset-0 rounded-full bg-teal-500/20 animate-ping"></span>

                <div class="absolute inset-0 animate-[spin_3s_linear_infinite]">
                    <span class="absolute top-0 left-1/2 -translate-x-1/2 w-5 h-2.5 rounded-full bg-linear-to-r from-emerald-400 to-white shadow-[0_0_10px_rgba(52,211,153,0.7)] rotate-20"></span>
                    <span class="absolute bottom-3 left-2 w-5 h-2.5 rounded-full bg-linear-to-r from-emerald-400 to-white shadow-[0_0_10px_rgba(52,211,153,0.7)] rotate-100"></span>
                    <span class="absolute bottom-3 right-2 w-5 h-2.5 rounded-full bg-linear-to-r from-emerald-400 to-white shadow-[0_0_10px_rgba(52,211,153,0.7)] -rotate-100"></span>
                </div>

                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative w-11 h-11 animate-pulse">
                        <div class="absolute left-1/2 top-0 -translate-x-1/2 w-3.5 h-11 rounded-md bg-teal-400 shadow-[0_0_12px_rgba(45,212,191,0.8)]"></div>
                        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-11 h-3.5 rounded-md bg-teal-400 shadow-[0_0_12px_rgba(45,212,191,0.8)]"></div>
                    </div>
                </div>
            </div>

            <div class="text-2xl font-bold tracking-wide text-green-600">
                MR<span class="text-teal-400">PHARMACY</span>
            </div>

            <div class="flex gap-1.5 -mt-2">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-bounce [animation-delay:0ms]"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-bounce [animation-delay:150ms]"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-bounce [animation-delay:300ms]"></span>
            </div>

        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(function () {
                    preloader.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(function () {
                        preloader.remove();
                    }, 500);
                }, 300);
            }
        });
    </script>
