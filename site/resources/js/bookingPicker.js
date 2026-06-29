/**
 * Alpine component: a check-in / check-out range calendar that greys out
 * past, blocked, and fully-booked dates so guests can only pick available
 * stays. It writes the selection into hidden check_in / check_out inputs and
 * exposes the live price summary.
 *
 * config: { price, commitment, unavailable: ['YYYY-MM-DD'], minDate, checkIn, checkOut }
 */
export default function bookingPicker(config) {
    const MONTH_NAMES = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December',
    ];
    const DOW = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

    const pad = (n) => String(n).padStart(2, '0');
    const toStr = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const parse = (s) => {
        const [y, m, d] = s.split('-').map(Number);
        return new Date(y, m - 1, d);
    };
    const addDays = (s, n) => {
        const d = parse(s);
        d.setDate(d.getDate() + n);
        return toStr(d);
    };

    return {
        price: config.price,
        commitment: config.commitment,
        unavailable: new Set(config.unavailable || []),
        minDate: config.minDate,
        checkIn: config.checkIn || '',
        checkOut: config.checkOut || '',
        viewYear: 0,
        viewMonth: 0,
        error: '',
        monthNames: MONTH_NAMES,
        dow: DOW,

        init() {
            const base = parse(this.checkIn && this.checkIn >= this.minDate ? this.checkIn : this.minDate);
            this.viewYear = base.getFullYear();
            this.viewMonth = base.getMonth();

            // Drop any prefilled range that is no longer fully available.
            if (this.checkIn && this.checkOut && !this.rangeAvailable(this.checkIn, this.checkOut)) {
                this.checkIn = '';
                this.checkOut = '';
            }
        },

        // ── Calendar grid ────────────────────────────────────────────────
        get monthLabel() {
            return `${this.monthNames[this.viewMonth]} ${this.viewYear}`;
        },

        get leadingBlanks() {
            return new Date(this.viewYear, this.viewMonth, 1).getDay();
        },

        get daysInMonth() {
            return new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
        },

        get days() {
            const out = [];
            for (let d = 1; d <= this.daysInMonth; d++) {
                out.push(toStr(new Date(this.viewYear, this.viewMonth, d)));
            }
            return out;
        },

        prevMonth() {
            const d = new Date(this.viewYear, this.viewMonth - 1, 1);
            this.viewYear = d.getFullYear();
            this.viewMonth = d.getMonth();
        },

        nextMonth() {
            const d = new Date(this.viewYear, this.viewMonth + 1, 1);
            this.viewYear = d.getFullYear();
            this.viewMonth = d.getMonth();
        },

        get canGoPrev() {
            // Don't page before the month containing minDate.
            const first = toStr(new Date(this.viewYear, this.viewMonth, 1));
            return first > this.minDate;
        },

        // ── Availability helpers ─────────────────────────────────────────
        // Are all nights in [from, to) bookable?
        rangeAvailable(from, to) {
            if (!from || !to || to <= from) return false;
            for (let d = from; d < to; d = addDays(d, 1)) {
                if (d < this.minDate || this.unavailable.has(d)) return false;
            }
            return true;
        },

        // Is this calendar cell disabled given the current selection phase?
        isDisabled(day) {
            const selectingEnd = this.checkIn && !this.checkOut;
            if (selectingEnd) {
                // Check-out: must be after check-in and keep every night available.
                if (day <= this.checkIn) return true;
                return !this.rangeAvailable(this.checkIn, day);
            }
            // Check-in (a night): must be today-or-later and available.
            return day < this.minDate || this.unavailable.has(day);
        },

        isCheckIn(day) { return day === this.checkIn; },
        isCheckOut(day) { return day === this.checkOut; },
        inRange(day) {
            return this.checkIn && this.checkOut && day > this.checkIn && day < this.checkOut;
        },

        select(day) {
            if (this.isDisabled(day)) return;
            this.error = '';

            // Start a fresh selection.
            if (!this.checkIn || (this.checkIn && this.checkOut)) {
                this.checkIn = day;
                this.checkOut = '';
                return;
            }

            // Completing the range (isDisabled already guaranteed availability).
            if (day > this.checkIn) {
                this.checkOut = day;
            } else {
                this.checkIn = day;
            }
        },

        clearDates() {
            this.checkIn = '';
            this.checkOut = '';
            this.error = '';
        },

        // ── Price summary ────────────────────────────────────────────────
        get nights() {
            if (!this.checkIn || !this.checkOut) return 0;
            return Math.max(0, Math.round((parse(this.checkOut) - parse(this.checkIn)) / 86400000));
        },
        get total() { return this.price * this.nights; },
        get fee() { return Math.round(this.total * this.commitment / 100); },
        get balance() { return this.total - this.fee; },
        get hasRange() { return this.nights > 0; },

        fmt(n) { return '₦' + n.toLocaleString('en-NG'); },
        pretty(s) {
            if (!s) return '—';
            return parse(s).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        },
    };
}
