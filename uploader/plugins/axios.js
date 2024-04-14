import axios from 'axios'
axios.defaults.baseURL = 'http://upload-service.test/backend/api' // Replace with your API base URL
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'multipart/form-data'

export default function ({ app, store }, inject) {
  const token = store?.state?.auth?.token
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  axios.interceptors.response.use(
    function (response) {
      const message = response?.data?.message || response?.data?.msg
      if (message && message != 'success') {
        app.$toast.success(message)
      }

      return response
    },
    function (error) {
      const message =
        error?.response?.data?.message ||
        error?.response?.data?.errors?.message ||
        error?.message
      if (
        message &&
        typeof message == 'string' &&
        error.response.status !== 401
      ) {
        app.$toast.error(message)
      } else if (error?.response?.status === 401) {
        store.commit('auth/SET_TOKEN', '')
        store.commit('auth/SET_USER', {})
        localStorage.removeItem('token')
        axios.defaults.headers.common['Authorization'] = ''
        if (window.location.pathname != '/login') {
          return app.router.push({ name: 'login' })
        }
      }
      return Promise.reject(error)
    }
  )

  inject('axios', axios)
}
