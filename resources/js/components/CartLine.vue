<template>
    <tr>
        <td>
            {{cartLine.timeslot}}
        </td>
        <td>
            {{this.doctor ? this.doctor.first_name + ' ' + this.doctor.last_name : ''}}
        </td>
        <td>
            {{this.room ? this.room : ''}}
        </td>
    </tr>
</template>

<script>
    import * as axios from 'axios'
    export default {
        name: 'CartLine',
        props: ['cartLine'],
        data () {
            return {
                doctor: {
                    first_name: '',
                    last_name: ''
                },
                room: {}
            }
        },
        created () {
            this.getDoctor(this.cartLine.doctor)
            this.getRoom(this.cartLine.room)
        },
        methods: {
            getDoctor (id) {
                const path = '/api/doctors/' + id
                axios({method: 'GET', 'url': path}).then(result => {
                    this.doctor = result.data[0]
                }, error => {
                    console.error(error)
                })
            },
            getRoom (id) {
                const path = '/api/rooms/' + id
                axios({method: 'GET', 'url': path}).then(result => {
                    this.room = result.data[0].location
                }, error => {
                    console.error(error)
                })
            }
        }
    }
</script>

<style scoped>
</style>
