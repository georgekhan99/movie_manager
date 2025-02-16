import { useStorage } from '@vueuse/core'
import { defineStore } from 'pinia'
import { ref } from 'vue'

// export const useDarkModeStore = defineStore('darkMode', () => {
//     const darkMode = useStorage('darkMode', ref(false))
  
//     document.documentElement.classList.toggle('dark', darkMode.value)
  
//     function toggleDarkMode() {
//       darkMode.value = !darkMode.value
//       document.documentElement.classList.toggle('dark', darkMode.value)
//     }
  
//     return { darkMode, toggleDarkMode }
//   })
  
// const getDateRange = (startDate: string): string => {
//     const start = new Date(startDate);
//     const end = new Date(start);
//     end.setDate(start.getDate() + 14); // Add 14 days to get the correct period
//     return `${formatDate(startDate)} - ${formatDate(end.toISOString().split("T")[0])}`;
//   };