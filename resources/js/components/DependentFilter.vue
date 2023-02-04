<template>
    <FilterContainer>
        <span>{{ filter.name }}</span>

        <template #filter>
            <SelectControl
                class="w-full block"
                size="sm"
                :dusk="`${filter.name}-select-filter`"
                :selected="currentValue"
                @change="value = $event"
                :options="filter.options"
                label="label"
            >
                <option value="" :selected="value == ''">{{ __('&mdash;') }}</option>
            </SelectControl>
        </template>
    </FilterContainer>
</template>

<script>
import debounce from 'lodash/debounce'

export default {
    emits: ['change'],

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

    data: () => ({
        value: null,
        debouncedHandleChange: null,
    }),

    created() {
        this.debouncedHandleChange = debounce(() => this.handleChange(), 500)
        this.setCurrentFilterValue()
    },

    mounted() {
        Nova.$on('filter-reset', this.setCurrentFilterValue)
    },

    beforeUnmount() {
        Nova.$off('filter-reset', this.setCurrentFilterValue)
    },

    watch: {
        value() {
            this.debouncedHandleChange()
        },
    },

    methods: {
        setCurrentFilterValue() {
            this.value = this.currentValue
        },

        handleChange() {
            this.$store.commit(`${this.resourceName}/updateFilterState`, {
                filterClass: this.filterKey,
                value: this.value,
            })
            this.$emit('change')

            this.filter.children.forEach(child => {
                this.fetchOptions({[this.filterKey]:this.value}, child).then(options => {
                    const filter = this.$store.getters[`${this.resourceName}/getFilter`](child)
                    let cv = filter.currentValue
                    cv && !options.some(option => option.value === cv) && (filter.currentValue = "")
                    filter.options = options
                })
            })
        },

        async fetchOptions(filters, filter = null) {
            const lens = this.lens ? `/lens/${this.lens}` : ''
            const {data: options} = await Nova.request().get(`/nova-api/${this.resourceName}${lens}/filters/options`, {
                params: {
                    filters: btoa(JSON.stringify(filters)),
                    filter: filter ?? this.filterKey,
                },
            })
            return options
        },
    },

    computed: {
        filter() {
            return this.$store.getters[`${this.resourceName}/getFilter`](this.filterKey)
        },
        currentValue() {
            return this.filter.currentValue
        },
    },
}
</script>