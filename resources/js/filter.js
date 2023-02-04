import Filter from './components/DependentFilter'

Nova.booting((app, store) => {
    app.component("dependent-filter", Filter)
})