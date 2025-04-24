<template>
    <FilterContainer>
        <span>
            {{ filter.name }}
        </span>

        <template #filter>
            <div
                v-if="filter.ranges"
                class="relative flex items-center justify-between mb-2"
            >
                <div class="relative flex w-full">
                    <select
                        v-model="currentRange"
                        @change="setNewRange()"
                        class="block w-full h-8 text-xs form-control form-control-bordered form-input"
                    >
                        <option selected="" value="">—</option>
                        <option
                            v-for="(range, index) in filter.ranges"
                            :key="index"
                            :value="index"
                        >
                            {{ range.name }}
                        </option>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-[11px] flex items-center"
                        ><svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                            data-slot="icon"
                            class="w-5 h-5 text-gray-700 shrink-0 dark:text-gray-400"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"
                            ></path></svg
                    ></span>
                </div>
            </div>
            <div class="relative flex items-center justify-between">
                <input
                    class="w-full form-control form-input form-control-bordered"
                    :disabled="disabled"
                    :class="{ '!cursor-not-allowed': disabled }"
                    :value="value"
                    :placeholder="placeholder"
                    ref="dateRangePicker"
                    name="date-range-filter"
                    autocomplete="off"
                    type="text"
                    :dusk="`${filter.name}-date-range-filter`"
                />
                <button
                    class="clear-button"
                    @click="clear()"
                    v-if="filter.currentValue"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z"
                        />
                    </svg>
                </button>
            </div>
        </template>
    </FilterContainer>
</template>
<script>
    import flatpickr from "flatpickr";

    require("flatpickr/dist/themes/light.css");

    export default {
        emits: ["change"],

        props: {
            resourceName: {
                type: String,
                required: true,
            },
            filterKey: {
                type: String,
                required: true,
            },
            lens: String,
        },

        data: () => ({ flatpickr: null, currentRange: "" }),

        computed: {
            placeholder() {
                return this.filter.placeholder || this.__("Pick a date range");
            },
            startDate() {
                return flatpickr.formatDate(
                    flatpickr.parseDate(
                        this.filter.currentValue[0],
                        this.dateFormat
                    ),
                    this.dateFormat
                );
            },
            endDate() {
                return flatpickr.formatDate(
                    flatpickr.parseDate(
                        this.filter.currentValue[1],
                        this.dateFormat
                    ),
                    this.dateFormat
                );
            },
            value() {
                if (
                    typeof this.filter.currentValue === "object" &&
                    this.filter.currentValue.length >= 2
                ) {
                    return `${this.startDate} ${this.separator} ${this.endDate}`;
                }
                return this.filter.currentValue || null;
            },
            filter() {
                let filter = this.$store.getters[
                    `${this.resourceName}/getFilter`
                ](this.filterKey);
                let ranges = filter.ranges;
                if (ranges) {
                    const match = ranges.findIndex(
                        (range) =>
                            range.start.startsWith(filter.currentValue[0]) &&
                            range.end.startsWith(filter.currentValue[1])
                    );

                    if (match !== -1) {
                        this.currentRange = match;
                    }
                }

                return filter;
            },
            options() {
                return this.$store.getters[
                    `${this.resourceName}/getOptionsForFilter`
                ](this.filterKey);
            },
            disabled() {
                return this.filter.disabled;
            },
            separator() {
                return this.filter.separator || "-";
            },
            modeType() {
                return this.filter.mode === "range" ? "range" : "single";
            },
            showMonths() {
                return this.filter.showMonths ?? "1";
            },
            minTime() {
                return this.filter.minTime ?? "00:00";
            },
            maxTime() {
                return this.filter.maxTime ?? "23:59";
            },
            dateFormat() {
                return (
                    this.filter.dateFormat ||
                    (this.filter.enableTime ? "Y-m-d H:i" : "Y-m-d")
                );
            },
            twelveHourTime() {
                return this.filter.twelveHourTime;
            },
            enableTime() {
                return this.filter.enableTime;
            },
            enableSeconds() {
                return this.filter.enableSeconds;
            },
            firstDayOfWeek() {
                return this.filter.firstDayOfWeek || 0;
            },
        },

        mounted() {
            const self = this;
            this.options.forEach((option) => {
                Object.assign(this.filter, { [option.label]: option.value });
            });

            this.$nextTick(() => {
                this.flatpickr = flatpickr(this.$refs.dateRangePicker, {
                    enableTime: this.enableTime,
                    enableSeconds: this.enableSeconds,
                    onClose: this.handleChange,
                    dateFormat: this.dateFormat,
                    allowInput: true,
                    mode: this.modeType,
                    showMonths: this.showMonths,
                    minTime: this.minTime,
                    maxTime: this.maxTime,
                    time_24hr: !this.twelveHourTime,
                    onReady() {
                        self.$refs.dateRangePicker.parentNode.classList.add(
                            "date-range-filter"
                        );
                    },
                    locale: {
                        rangeSeparator: ` ${this.separator} `,
                        firstDayOfWeek: this.firstDayOfWeek,
                    },
                });
            });
        },

        methods: {
            setNewRange(event) {
                let range = this.filter.ranges[this.currentRange];

                this.updateFilterState(
                    this.mapValues([
                        this.formatDate(range.start),
                        this.formatDate(range.end),
                    ])
                );

                this.flatpickr.setDate([range.start, range.end], false);
            },
            formatDate(date) {
                return new Date(date.replace(" ", "T") + "");
            },
            mapValues(range) {
                return range.map((value) => {
                    return flatpickr.formatDate(value, this.dateFormat);
                });
            },
            clear() {
                this.flatpickr.clear();
                this.handleChange([]);
            },
            isEmptyArray(value) {
                return Array.isArray(value) && value.length == 0;
            },
            handleChange(event) {
                let value = this.mapValues(event);

                if (this.isEmptyArray(value)) {
                    value = "";
                }

                this.updateFilterState(value);
            },
            updateFilterState(value) {
                this.$store.commit(`${this.resourceName}/updateFilterState`, {
                    filterClass: this.filterKey,
                    value,
                });

                this.$emit("change");
            },
        },
    };
</script>
<style>
    .\!cursor-not-allowed {
        cursor: not-allowed !important;
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.startRange.prevMonthDay,
    .flatpickr-day.endRange.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay,
    .flatpickr-day.startRange.nextMonthDay,
    .flatpickr-day.endRange.nextMonthDay {
        background: #0ca5e9;
        border-color: #0ca5e9;
    }

    .flatpickr-monthDropdown-months,
    .numInput.cur-year,
    .flatpickr-current-month input.cur-year,
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-size: 1rem;
        line-height: 1rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    .clear-button {
        position: absolute;
        right: 6px;
    }
</style>
