<template>
  <greeting />

  <responsive-card>
    <introduction-section />
    <location-section class="mt-2" />
    <text-section section-name="excavation" class="mt-2" />
    <text-section section-name="website" class="mt-2" />
    <carousel class="mt-2" />
  </responsive-card>

  <my-footer />
</template>

<script setup lang="ts">
import Carousel from '../landing/GallerySection.vue'
import ResponsiveCard from '../landing/ResponsiveCard.vue'
import MyFooter from '../landing/MyFooter.vue'
import Greeting from '../landing/GreetingSection.vue'
import TextSection from '../landing/TextSection.vue'
import IntroductionSection from '../landing/IntroductionSection.vue'
import LocationSection from '../landing/LocationSection.vue'
</script>
