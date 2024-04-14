<template>
  <v-dialog v-model="dialog" width="500">
    <v-card :loading="loading">
      <v-card-title>
        <span class="text-h5">Uploader</span>
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="12" sm="12">
            <v-file-input
              truncate-length="15"
              label="File input"
              show-size
              v-model="file"
              :error-messages="validationErrors['file_name']"
            ></v-file-input>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" :loading="loading" text @click="close()">
          Close
        </v-btn>
        <v-btn
          color="blue darken-1"
          :loading="loading"
          text
          @click="uploadFile()"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      file: null,
      loading: false,
      validationErrors: [],
    }
  },

  methods: {
    open() {
      this.file = null
      this.dialog = true
      this.validationErrors = []
    },
    close() {
      this.file = null
      this.dialog = false
      this.validationErrors = []
    },
    async uploadFile() {
      try {
        this.loading = true
        this.validationErrors = []
        const form = new FormData()
        form.set('file_name', this.file)
        await this.$axios.post('files', form)
        this.loading = false
        this.close()
        this.$emit('upload-complete')
      } catch (error) {
        this.loading = false
        if (error?.response?.status == 422)
          this.validationErrors = error?.response?.data?.errors
      }
    },
  },
}
</script>
