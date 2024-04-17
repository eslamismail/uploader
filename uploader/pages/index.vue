<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="12" md="12">
      <v-card flat :loading="loading">
        <v-card-title>
          <span> Files </span>
          <v-spacer></v-spacer>
          <v-btn color="primary" small :loading="loading" @click="openUploader">
            <v-icon>mdi-plus</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-data-table :loading="loading" :headers="headers" :items="files">
            <template v-slot:item.file_name="{ item }">
              <a target="_blank" v-if="item.file_name" :href="item.file_name">
                open
              </a>
            </template>
            <template v-slot:item.created_at="{ item }">
              {{ moment(item.created_at).format('lll') }}
            </template>
            <template v-slot:item.updated_at="{ item }">
              {{ moment(item.updated_at).format('lll') }}
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn
                color="error"
                :loading="loading"
                small
                @click="deleteFile(item)"
              >
                delete
              </v-btn>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
      <Uploader ref="uploader" @upload-complete="initPage()" />
    </v-col>
  </v-row>
</template>

<script>
const moment = require('moment')
import Uploader from '@/components/Uploader'
export default {
  name: 'IndexPage',
  components: { Uploader },
  data() {
    return {
      loading: false,
      files: [],
      moment,
    }
  },

  computed: {
    headers() {
      const keys = Object.keys(this.files[0] ?? {}).map((item) => ({
        text: item.replace('_', ' ').toUpperCase(),
        value: item,
        align: 'start',
        sortable: true,
      }))
      if (keys.length)
        keys.push({
          text: 'action'.replace('_', ' ').toUpperCase(),
          align: 'start',
          sortable: false,
          value: 'action',
        })
      return keys
    },
  },
  created() {
    this.initPage()
  },
  methods: {
    async initPage() {
      this.loading = true
      try {
        const response = await this.$axios.get('files')
        this.files = response?.data?.files
      } catch (error) {}
      this.loading = false
    },
    openUploader() {
      this.$refs['uploader'].open()
    },

    async deleteFile(item) {
      const { id } = item
      this.loading = true
      try {
        await this.$axios.delete(`files/${id}`)
      } catch (error) {}
      this.loading = false
      this.initPage()
    },
  },
}
</script>
