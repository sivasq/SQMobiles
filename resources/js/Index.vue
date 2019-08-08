<template>
    <div id="app">
        <header id="header">
            <Menu></Menu>
        </header>
        <transition mode="out-in" name="fade">
            <div id="content" style="margin-top: 100px;">
                <router-view></router-view>
            </div>
        </transition>
    </div>
</template>
<script>
    import Menu from './components/Menu.vue'

    export default {
        data() {
            return {
                //
            }
        },
        components: {
            Menu
        }
    }
</script>
