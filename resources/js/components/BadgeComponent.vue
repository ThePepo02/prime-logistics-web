<template>
  <span class="badge" :class="{ 'empty': count === 0 }">
    {{ count }}
  </span>
</template>

<script>
/**
 * BadgeComponent - Componente pequeño para mostrar conteos
 */
export default {
  name: 'BadgeComponent',
  
  props: {
    count: {
      type: Number,
      default: 0
    }
  }
};
</script>

<style scoped>
.badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 24px;
  height: 24px;
  padding: 0 6px;
  background-color: #667eea;
  color: white;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.badge.empty {
  background-color: #ccc;
  color: #999;
}
</style>
