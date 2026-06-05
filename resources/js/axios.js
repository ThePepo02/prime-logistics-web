import axios from 'axios'

// Configuramos la URL base para no repetirla en cada llamada
axios.defaults.baseURL = '/api'

// Antes de cada petición, axios añade automáticamente el token de Sanctum
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

export default axios
