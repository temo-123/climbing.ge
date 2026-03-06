import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user
  },

  actions: {
    async login(credentials) {
      this.loading = true
      this.error = null
      
      try {
        const config = useRuntimeConfig()
        const response = await axios.post(`${config.public.apiBaseUrl}/login`, credentials)
        
        this.token = response.data.token
        this.user = response.data.user
        
        // Store token in localStorage for persistence
        if (import.meta.client) {
          localStorage.setItem('auth_token', this.token)
          localStorage.setItem('user', JSON.stringify(this.user))
        }
        
        // Set default Authorization header
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        return response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Login failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async register(userData) {
      this.loading = true
      this.error = null
      
      try {
        const config = useRuntimeConfig()
        const response = await axios.post(`${config.public.apiBaseUrl}/register`, userData)
        
        this.token = response.data.token
        this.user = response.data.user
        
        if (import.meta.client) {
          localStorage.setItem('auth_token', this.token)
          localStorage.setItem('user', JSON.stringify(this.user))
        }
        
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        return response.data
      } catch (error) {
        this.error = error.response?.data?.message || 'Registration failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        const config = useRuntimeConfig()
        if (this.token) {
          await axios.post(`${config.public.apiBaseUrl}/logout`)
        }
      } catch (error) {
        // Ignore logout errors
      } finally {
        this.token = null
        this.user = null
        
        if (import.meta.client) {
          localStorage.removeItem('auth_token')
          localStorage.removeItem('user')
        }
        
        delete axios.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      if (!this.token) return null
      
      this.loading = true
      
      try {
        const config = useRuntimeConfig()
        const response = await axios.get(`${config.public.apiBaseUrl}/user`)
        
        this.user = response.data
        if (import.meta.client) {
          localStorage.setItem('user', JSON.stringify(this.user))
        }
        
        return response.data
      } catch (error) {
        this.logout()
        throw error
      } finally {
        this.loading = false
      }
    },

    initAuth() {
      if (import.meta.client) {
        const token = localStorage.getItem('auth_token')
        const userStr = localStorage.getItem('user')
        
        if (token && userStr) {
          this.token = token
          this.user = JSON.parse(userStr)
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        }
      }
    }
  }
})

