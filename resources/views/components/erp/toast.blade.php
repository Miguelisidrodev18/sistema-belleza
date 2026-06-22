<div
    x-data="{
        toasts: [],
        add(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.remove(id), 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @toast.window="add($event.detail.message, $event.detail.type || 'success')"
    class="fixed right-4 top-20 z-[60] flex flex-col gap-2"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium shadow-lg"
            :class="{
                'bg-green-50 text-green-800 border border-green-200': toast.type === 'success',
                'bg-red-50 text-red-800 border border-red-200': toast.type === 'error',
                'bg-yellow-50 text-yellow-800 border border-yellow-200': toast.type === 'warning',
                'bg-blue-50 text-blue-800 border border-blue-200': toast.type === 'info'
            }"
        >
            <span x-text="toast.message"></span>
            <button @click="remove(toast.id)" class="ml-2 opacity-50 hover:opacity-100">&times;</button>
        </div>
    </template>
</div>
