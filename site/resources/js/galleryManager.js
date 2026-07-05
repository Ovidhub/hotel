/**
 * Alpine component: admin gallery manager.
 *
 * Lets an admin add, remove and reorder gallery images before saving.
 * - Existing images are seeded from the server as { value, url } pairs.
 * - New images are picked client-side and previewed instantly.
 *
 * On submit it writes two things the server understands:
 *   • gallery_order  – a JSON list of tokens in display order. Existing
 *                      images use their stored path; new images use the
 *                      placeholder "__new__:N" (N = position among new files).
 *   • gallery_files[] – the real <input type=file>, rebuilt (via DataTransfer)
 *                      so its files match the "__new__:N" indices exactly.
 *
 * config: { existing: [{ value, url }] }
 */
export default function galleryManager(config) {
    return {
        seq: 0,
        items: [],

        init() {
            // Seed existing images, each with a stable unique id.
            this.items = (config.existing || []).map((e) => ({
                id: 'e' + this.seq++,
                kind: 'existing',
                value: e.value,
                url: e.url,
            }));
            this.$nextTick(() => this.sync());
        },

        addFiles(event) {
            const files = Array.from(event.target.files || []);
            for (const file of files) {
                this.items.push({
                    id: 'n' + this.seq++,
                    kind: 'new',
                    file,
                    url: URL.createObjectURL(file),
                });
            }
            event.target.value = ''; // allow re-picking the same file & accumulating
            this.sync();
        },

        remove(id) {
            const item = this.items.find((i) => i.id === id);
            if (item && item.kind === 'new' && item.url) {
                URL.revokeObjectURL(item.url);
            }
            this.items = this.items.filter((i) => i.id !== id);
            this.sync();
        },

        move(index, direction) {
            const target = index + direction;
            if (target < 0 || target >= this.items.length) return;
            const arr = this.items;
            [arr[index], arr[target]] = [arr[target], arr[index]];
            this.sync();
        },

        moveToStart(index) {
            if (index <= 0) return;
            const [item] = this.items.splice(index, 1);
            this.items.unshift(item);
            this.sync();
        },

        moveToEnd(index) {
            if (index >= this.items.length - 1) return;
            const [item] = this.items.splice(index, 1);
            this.items.push(item);
            this.sync();
        },

        /** JSON list of ordered tokens, bound to the hidden gallery_order input. */
        orderJson() {
            let n = 0;
            return JSON.stringify(
                this.items.map((it) => (it.kind === 'new' ? '__new__:' + n++ : it.value))
            );
        },

        /** Rebuild the real file input so uploaded files match the token order. */
        sync() {
            const input = this.$refs.fileInput;
            if (!input) return;
            const dt = new DataTransfer();
            for (const it of this.items) {
                if (it.kind === 'new') dt.items.add(it.file);
            }
            input.files = dt.files;
        },
    };
}
