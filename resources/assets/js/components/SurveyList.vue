<template>
    <div>
        <v-toolbar>
            <v-toolbar-title>List of all available surveys</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-dialog flat v-model="dialog" max-width="80%" content-class="remove-overflow">
                <v-btn slot="activator" color="primary" dark class="mb-2">New Survey</v-btn>
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="editedItem.name"
                                            label="Survey name"
                                            :rules="[
                                            () => !!editedItem.name || 'The field name is required',
                                            () => !!editedItem.name && editedItem.name.length >= 3 || 'Name must contain at least 3 character!',
                                            ]"
                                    >

                                    </v-text-field>

                                    <v-textarea
                                        v-model="editedItem.description"
                                        label="Survey description"
                                    >

                                    </v-textarea>


                                  <v-layout row wrap>
                                    <v-flex xs12 sm6 md6>
                                      <v-menu
                                          v-model="menu1"
                                          :close-on-content-click="false"
                                          :nudge-right="40"
                                          lazy
                                          transition="scale-transition"
                                          offset-y
                                          width="290px"
                                      >
                                        <template v-slot:activator="{ on }">
                                          <v-text-field
                                              v-model="editedItem.start_date"
                                              label="Start date"
                                              prepend-icon="event"
                                              readonly
                                              v-on="on"
                                          ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="editedItem.start_date" @input="menu1 = false" :min="new Date().toISOString().slice(0,10)"></v-date-picker>
                                      </v-menu>
                                    </v-flex>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 sm6 md6>
                                      <v-menu
                                          ref="menu2"
                                          v-model="menu2"
                                          :close-on-content-click="false"
                                          :nudge-right="40"
                                          :return-value.sync="editedItem.start_time"
                                          lazy
                                          transition="scale-transition"
                                          offset-y
                                          width="290px"
                                      >
                                        <template v-slot:activator="{ on }">
                                          <v-text-field
                                              v-model="editedItem.start_time"
                                              label="Start time"
                                              prepend-icon="access_time"
                                              readonly
                                              v-on="on"
                                          ></v-text-field>
                                        </template>
                                        <v-time-picker
                                            v-if="menu2"
                                            v-model="editedItem.start_time"
                                            full-width
                                            @click:minute="$refs.menu2.save(editedItem.start_time)"
                                        ></v-time-picker>
                                      </v-menu>
                                    </v-flex>



                                    <v-flex xs12 sm6 md6>
                                      <v-menu
                                          v-model="menu3"
                                          :close-on-content-click="false"
                                          :nudge-right="40"
                                          lazy
                                          transition="scale-transition"
                                          offset-y
                                          width="290px"
                                      >
                                        <template v-slot:activator="{ on }">
                                          <v-text-field
                                              v-model="editedItem.end_date"
                                              label="End date"
                                              prepend-icon="event"
                                              readonly
                                              v-on="on"
                                          ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="editedItem.end_date" @input="menu3 = false" :min="new Date().toISOString().slice(0,10)"></v-date-picker>
                                      </v-menu>
                                    </v-flex>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 sm6 md6>
                                      <v-menu
                                          ref="menu4"
                                          v-model="menu4"
                                          :close-on-content-click="false"
                                          :nudge-right="40"
                                          :return-value.sync="editedItem.end_time"
                                          lazy
                                          transition="scale-transition"
                                          offset-y
                                          width="290px"
                                      >
                                        <template v-slot:activator="{ on }">
                                          <v-text-field
                                              v-model="editedItem.end_time"
                                              label="End time"
                                              prepend-icon="access_time"
                                              readonly
                                              v-on="on"
                                          ></v-text-field>
                                        </template>
                                        <v-time-picker
                                            v-if="menu4"
                                            v-model="editedItem.end_time"
                                            full-width
                                            @click:minute="$refs.menu4.save(editedItem.end_time)"
                                        ></v-time-picker>
                                      </v-menu>
                                    </v-flex>

                                  </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click.native="onCloseModal">Cancel</v-btn>
                        <v-btn color="blue darken-1" flat @click.native="onSaveModal(editedItem.name,editedItem.description,editedItem.start_date,editedItem.start_time,editedItem.end_date,editedItem.end_time)">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table
                :headers="headers"
                :items="surveys"
                :loading="loading"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td class="text-sm-left">{{ props.item.id }}</td>
                <td class="text-sm-left">{{ props.item.name }}</td>
                <td class="text-sm-left">{{ props.item.description }}</td>
                <td class="text-sm-left">{{ props.item.created_at}}</td>
                <td class="text-sm-left">{{ props.item.start_date}} {{props.item.start_time}}</td>
                <td class="text-sm-left">{{ props.item.end_date}}  {{ props.item.end_time}}</td>
                <td class="justify-center layout px-0">
                    <v-btn icon class="mx-0" @click="runSurvey(props.item.slug)">
                        <v-icon color="teal">play_circle_outline</v-icon>
                    </v-btn>
                    <v-btn icon class="mx-0" @click="showResults(props.item.id)">
                        <v-icon color="indigo">question_answer</v-icon>
                    </v-btn>
                    <v-btn icon class="mx-0" @click="editItem(props.item.id)">
                        <v-icon color="amber">edit</v-icon>
                    </v-btn>
                    <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                        <v-icon color="pink">delete</v-icon>
                    </v-btn>
                </td>
            </template>
            <template slot="no-data">
                <v-btn color="primary" @click="getSurveys">Reset</v-btn>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination v-model="page" :length="pageLength" :total-visible="7"></v-pagination>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'survey-list',
        data () {
            return {
                menu1: false,
                menu2: false,
                menu3: false,
                menu4: false,
                surveys: [],
                page: 1,
                pageLength: 1,
                dialog: false,
                loading: false,
                formTitle: 'New Survey',
                headers: [
                    {
                        text: 'ID',
                        alignt: 'left',
                        value: 'id',
                        sortable: false
                    },
                    {
                        text: 'Name',
                        value: 'name',
                        sortable: false
                    },
                    {
                      text: 'Description',
                      value: 'description',
                      sortable: false
                    },
                    {
                        text: 'Created date',
                        value: 'created_at',
                        sortable: false
                    },
                    {
                      text: 'Start date time',
                      value: 'start_date ' + ' start_time',
                      sortable: false
                    },
                    {
                      text: 'End date time',
                      value: 'end_date ' + ' end_time',
                      sortable: false
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    }
                ],
                editedItem: {
                    name: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    start_time: null,
                    end_time: null,
                },

            }
        },
        mounted() {
            this.getSurveys();
        },
        watch: {
            page() {
                this.getSurveys();
            }
        },
        methods: {
            getSurveys() {
                this.loading = true;
                axios.get('/survey', {
                    params: {
                        page: this.page
                    }
                })
                    .then((response) => {
                        if(response.status === 200) {
                            this.surveys = response.data.data;
                            this.pageLength = Math.ceil(response.data.meta.total / response.data.meta.per_page);
                            this.loading = false;
                        }
                    })
                    .catch((error) => {
                        this.loading = false;
                        console.info(error.response);
                    })
            },
            editItem(id) {
                this.$router.push({name: 'editor', params: {id: id}})
            },
            deleteItem(item) {
                if(confirm('Are you sure you want to delete this survey?')) {
                    this.snackbar = true;
                    axios.delete('/survey/' + item.id)
                        .then((response) => {
                            if(response.status === 200) {
                                this.$root.snackbarMsg = response.data.message;
                                this.$root.snackbar = true;
                            }
                        });
                    const index = this.surveys.indexOf(item);
                    this.surveys.splice(index, 1);
                }
            },
            onCloseModal() {
                this.dialog = false;
                this.editedItem = Object.assign({}, {name: ''})
            },
            onSaveModal(name,description,start_date,start_time,end_date,end_time) {
                this.loading = true;
                let data = {
                    name: name,
                    description: description,
                    start_date: start_date,
                    start_time: start_time,
                    end_date: end_date,
                    end_time: end_time,
                    json: {
                        pages: []
                    }
                };
                axios.post('/survey', data)
                    .then((response) => {
                        if(response.status === 201) {
                            this.dialog = false;
                            this.loading = false;
                            this.$root.snackbarMsg = response.data.message;
                            this.$root.snackbar = true;
                            this.editedItem = Object.assign({}, {name: '', description: '',start_date: '',start_time: '',end_date: '',end_time: ''});
                            this.getSurveys();
                        }
                    })
            },
            runSurvey(slug) {
                window.open('/' + SurveyConfig.route_prefix + '/' + slug, '_blank');
            },
            showResults(id) {
                this.$router.push({name: 'result', params: {id: id} })
            }
        }
    }
</script>

<style>
    .remove-overflow {
        overflow: inherit;
    }
</style>