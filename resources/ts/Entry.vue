<template>
    <n-config-provider :theme="darkTheme">
        <n-notification-provider :max="3">
            <n-message-provider>
                <n-dialog-provider>
                    <router-view/>
                </n-dialog-provider>
            </n-message-provider>
        </n-notification-provider>
    </n-config-provider>
</template>
<script setup lang="ts">
import {darkTheme} from 'naive-ui'
</script>
<style>
body {
    background: #000;
}
</style>
