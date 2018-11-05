<template>
  <div>
    <notifications
      animation-type="velocity"
      :animation="animation"
      position="bottom right"
    />
  </div>
</template>

<script>
export default {
  data: () => ({
    animation: {
      enter: ({ clientHeight }) => ({
        height: [clientHeight, 0],
        opacity: 1,
      }),
      leave: {
        height: 0,
        opacity: 0,
      },
    },
  }),
};
</script>
