<template>
  <div class="q-pa-md">
    <!-- card -->
    <q-card class="my-card">
      <!-- <q-card-section class="bg-primary row justify-apart q-py-xs">
        <y class="col text-white q-mt-sm"
          >Upload photos {{ images.length }}/ 4</y
        >
        <y class="col col-shrink">
          <q-btn flat round dense color="white" size="md" icon="cloud_upload" />
        </y>
      </q-card-section> -->
      <div class="q-pa-sm row justify-center">
        <q-btn icon="fas fa-folder" @click="selectImage">
          <input
            hidden
            ref="fileInput"
            type="file"
            accept="image/*"
            @input="pickFile"
            multiple
          />
          &nbsp; Select Photos: {{ images.length }}
        </q-btn>
      </div>
      <q-scroll-area class="scroll" v-if="images.length > 0">
        <q-list separator>
          <q-item v-for="image in images">
            <q-item-section>
              <q-img :src="image.data" />
            </q-item-section>

            <q-item-section top side>
              <q-btn
                class="gt-xs text-red-14"
                size="12px"
                flat
                dense
                round
                icon="fas fa-trash"
                @click="removeFile(image.id)"
              />
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>

      <q-card-section class="q-py-xs row justify-center">
        <y class="">
          <q-btn
            v-if="images.length > 3"
            class="bg-primary gt-xs"
            flat
            color="white"
            icon="cloud_upload"
            label="Upload"
          />

          <y v-if="images.length <= 3">
            <q-icon flat color="yellow-10" name="warning" round>&nbsp; </q-icon>
            <y class="text-yellow-10"> SELECT AT LEAST 4 PHOTOS</y>
          </y>
        </y>
      </q-card-section>
    </q-card>

    <!-- card -->
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
export default {
  name: 'Uploader',
  data() {
    return {
      previewImage: null,
      images: [],
    };
  },
  methods: {
    selectImage() {
      this.$refs.fileInput.click();
    },
    pickFile() {
      let input = this.$refs.fileInput;
      let files = input.files;
      // console.log(file);
      if (files && files[0]) {
        for (var i = 0; i <= files.length; i++) {
          let reader = new FileReader();
          let file = files[i];
          // console.log(file);

          reader.onload = (e) => {
            this.previewImage = e.target.result;

            this.images.unshift({
              id: Date.now(),
              data: this.previewImage,
            });
            console.log(this.images);
          };
          reader.readAsDataURL(file);
          this.$emit('input', file);
        }
      }
    },

    removeFile(f) {
      this.images = this.images.filter((item) => {
        return item.id !== f;
      });
    },
  },
};
</script>

<style scoped lang="css">
.imagePreviewWrapper {
  height: auto;
  width: auto;
  display: block;
  cursor: pointer;
  margin: 0 auto 30px;
  position: relative;
}

.my-card {
  max-width: 300px;
}
.myImage {
  max-height: 200px;
}
.scroll {
  width: 300px;
  height: 250px;
}
</style>
