// auth.js
//handles and stores user's login and capabilities
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type {
  TLoginForm,
  TRegistrationForm,
  TForgotPasswordForm,
  TResetPasswordForm,
  TUser,
} from '@/types/authTypes'

import type { TPageName } from '@/types/routesTypes'
import type { TXhrEmptyResult, TXhrResult } from '@/types/xhrTypes'
import { useXhrStore } from './xhr'
import { useRoutesMainStore } from './routes/routesMain'

export const useAuthStore = defineStore('auth', () => {
  const { send } = useXhrStore()

  const user = ref<TUser | null>(null)
  const accessibility = ref({ authenticatedUsersOnly: true, readOnly: false })
  const dialog = ref({ open: false, message: '' })

  const authenticated = computed(() => {
    return user.value !== null
  })

  const permissions = computed(() => {
    return user.value === null ? [] : user.value.permissions
  })

  function openDialog(message: string) {
    dialog.value = { open: true, message }
  }

  async function logout(): Promise<TXhrEmptyResult> {
    console.log('auth.logout')
    user.value = null
    return await send<TUser>('fortify/logout', 'post')
  }

  async function register(form: TRegistrationForm): Promise<TXhrEmptyResult> {
    console.log('auth.register()')
    user.value = null
    return await send('fortify/register', 'post', form)
  }

  async function getUser(): Promise<TXhrResult<TUser>> {
    console.log('auth.getUser()')
    const res = await send<TUser>('about/me', 'get')
    if (res.success) {
      return res
    } else {
      return { success: false, message: res.message, status: res.status }
    }
  }

  async function loginGetUser(form: TLoginForm): Promise<TXhrResult<TUser>> {
    user.value = null
    const res = await send<{ two_factor: boolean }>('fortify/login', 'post', form)
    if (res.success) {
      return await getUser()
    } else {
      return res
    }
  }

  //currently not used, maybe later add option to re-send verification email
  async function sendVerificationNatification(): Promise<TXhrEmptyResult> {
    console.log('auth.sendVerificationNatification')
    return await send('fortify/email/verification-notification', 'post')
  }

  async function forgotPassword(form: TForgotPasswordForm): Promise<TXhrEmptyResult> {
    console.log('auth.forgotPassword()')
    return await send('fortify/forgot-password', 'post', form)
  }

  async function resetPassword(form: TResetPasswordForm): Promise<TXhrEmptyResult> {
    console.log('auth.resetPassword()')
    return send('fortify/reset-password', 'post', form)
  }

  async function resetAndGoTo(routeName: TPageName | null = null) {
    const { routerPush } = useRoutesMainStore()
    dialog.value = { open: false, message: '' }
    if (routeName !== null) {
      await routerPush(routeName)
    }
  }

  return {
    register,
    loginGetUser,
    getUser,
    forgotPassword,
    resetPassword,
    logout,
    resetAndGoTo,
    sendVerificationNatification,
    openDialog,
    dialog,
    user,
    accessibility,
    authenticated,
    permissions,
  }
})
