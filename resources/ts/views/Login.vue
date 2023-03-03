<template>
    <n-layout>
        <n-layout-content id="login-wrapper">
            <n-form aria-autocomplete="none" id="login-form" ref="formRef" :model="formValue" :rules="rules">
                <n-h1>
                    Login
                </n-h1>

                <n-form-item label="Email" path="email">

                    <n-input v-model:value="formValue.email" placeholder="Input Email" autocomplete="chrome-off">
                        <template #prefix>
                            <n-icon :component="Mail" />
                        </template>
                    </n-input>
                </n-form-item>
                <n-form-item label="Password" path="password">
                    <n-input v-model:value="formValue.password" placeholder="Input Password" type="password"
                        show-password-on="mousedown" autocomplete="chrome-off">
                        <template #prefix>
                            <n-icon :component="LockClosed" />
                        </template>
                    </n-input>
                </n-form-item>
                <n-form-item>
                    <n-button :loading="loading" type="info" @click="handleValidateClick">
                        Login
                    </n-button>
                </n-form-item>
            </n-form>
        </n-layout-content>
    </n-layout>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { FormInst, useMessage } from 'naive-ui'
import { Mail, LockClosed } from '@vicons/ionicons5'
import { useRouter } from "vue-router";
import { AuthService } from "../services/AuthService";
import { Helpers } from "../helpers";

const authService = new AuthService()
const loading = ref<boolean>(false)
const formRef = ref<FormInst | null>(null)
const message = useMessage()
const router = useRouter()

const rules = {
    email: {
        required: true,
        message: 'Please input your email',
        trigger: ['blur']
    },
    password: {
        required: true,
        message: 'Please input your password',
        trigger: ['blur']
    }
}

function handleValidateClick(e: MouseEvent) {
    loading.value = true
    e.preventDefault()
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            const body: LoginBody = {
                email: formValue.value.email,
                password: formValue.value.password,
            }
            const res = await authService.login(body).finally(() => loading.value = false)
            if (res.errors) {
                message.error(new Helpers().generateErrors(res.errors))
                return
            }
            else {
                message.info("Logged in successfully")
                router.push({ name: 'Dashboard' })
            }
        } else {
            message.error('Please make sure to enter all required information',)
        }
    })
}

const formValue = ref({
    email: '',
    password: ''
})
</script>
<style>
#login-wrapper {
    display: flex;
    height: 100vh;
    justify-content: center;
    align-items: center;
}

#login-form {
    width: 400px;
}

#login-wrapper .n-layout,
#login-wrapper .n-layout-scroll-container {
    display: flex;
    height: 100vh;
    justify-content: center;
    align-items: center;
}
</style>
