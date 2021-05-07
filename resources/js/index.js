Nova.booting((Vue, router) => {
    Vue.component(
        "nova-date-range-filter",
        require("./components/NovaDateRangeFilter.vue").default
    );
});