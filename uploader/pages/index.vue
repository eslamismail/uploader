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
            <template v-slot:item.file_link="{ item }">
              <a target="_blank" v-if="item.file_name" :href="item.file_name"
                >open</a
              >
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn color="error" small @click="deleteFile(item)">
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
      headers: [
        {
          text: 'file name',
          align: 'start',
          sortable: true,
          value: 'name',
        },
        {
          text: 'file size',
          align: 'start',
          sortable: true,
          value: 'file_size',
        },
        {
          text: 'file link',
          align: 'start',
          sortable: true,
          value: 'file_link',
        },
        {
          text: 'created at',
          align: 'start',
          sortable: true,
          value: 'created_at',
        },
        {
          text: 'updated at',
          align: 'start',
          sortable: true,
          value: 'updated_at',
        },
        {
          text: 'action',
          align: 'start',
          sortable: true,
          value: 'action',
        },
      ],
    }
  },

  created() {
    this.initPage()
  },
  methods: {
    async initPage() {
      this.loading = true
      try {
        this.headers = this.headers.map((item) => ({
          ...item,
          text: item.text.toUpperCase(),
        }))
        const response = await this.$axios.get('files')
        this.files = response?.data?.files.map((item) => ({
          ...item,
          created_at: moment(item.created_at).format('lll'),
          updated_at: moment(item.updated_at).format('lll'),
        }))
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
