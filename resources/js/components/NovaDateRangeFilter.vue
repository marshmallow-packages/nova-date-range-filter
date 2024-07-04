<template>
    <FilterContainer>
        <span>
            {{ filter.name }}
        </span>

        <template #filter>
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
        </template>
    </FilterContainer>
</template>
<script>
    import flatpickr from "flatpickr";
    require("flatpickr/dist/themes/airbnb.css");

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

        data: () => ({ flatpickr: null }),

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
                return this.$store.getters[`${this.resourceName}/getFilter`](
                    this.filterKey
                );
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
            handleChange(event) {
                let value = event.map((value) => {
                    return flatpickr.formatDate(value, this.dateFormat);
                });

                this.$store.commit(`${this.resourceName}/updateFilterState`, {
                    filterClass: this.filterKey,
                    value,
                });

                this.$emit("change");
            },
        },
    };
</script>
<style scoped lang="scss">
    .\!cursor-not-allowed {
        cursor: not-allowed !important;
    }
</style>
