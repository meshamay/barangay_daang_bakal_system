<nav class="bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 p-4">
  <div class="flex items-center justify-between">
    <span class="text-white text-xl font-bold">Barangay Daang Bakal</span>
    <button @click="open = !open" x-data="{ open: false }" class="text-white focus:outline-none">
      <svg :class="{'rotate-90': open}" class="w-8 h-8 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>
  <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-20 right-0 left-0 bg-white/95 backdrop-blur-md text-slate-900 p-6 shadow-xl z-50 flex flex-col gap-2 border-t-2 border-blue-100 rounded-2xl">
    <a href="#hero" class="font-bold text-base py-3 px-4 hover:bg-blue-50 rounded-lg transition text-slate-600 shadow-sm">Home</a>
    <a href="#services" class="font-bold text-base py-3 px-4 hover:bg-blue-50 rounded-lg transition text-slate-600 shadow-sm">Services</a>
    <a href="#about" class="font-bold text-base py-3 px-4 hover:bg-blue-50 rounded-lg transition text-slate-600 shadow-sm">About</a>
    <div class="pt-2 border-t border-slate-200 mt-2 flex justify-center">
      <a href="/login" class="font-bold text-base py-3 px-4 bg-white text-slate-600 rounded-lg transition text-center hover:shadow-lg border border-gray-300 shadow-sm">Login</a>
    </div>
  </div>
</nav>
<script src="https://unpkg.com/alpinejs" defer></script>
    <a href="/about" class="flex items-center gap-3 px-5 py-3 hover:bg-blue-50 font-medium text-base transition">
      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6" /></svg>
      About
    </a>
    <a href="/login" class="flex items-center gap-3 px-5 py-3 hover:bg-blue-50 font-medium text-base transition">
      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m12 0a4 4 0 100-8 4 4 0 000 8zm0 0v8" /></svg>
      Login
    </a>
  </div>
</nav>

<script src="https://unpkg.com/alpinejs" defer></script>
