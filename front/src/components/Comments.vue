<template>
    <div class="container">
        <comment :comment="comment" :ip="ip" v-for="comment in comments">
            {{comment.username}}
        </comment>
    </div>
</template>

<script type="text/babel">
import axios from 'axios'
import Comment from './components/Comment.vue'
export default {

  props:{
      id: Number,
      model: String,
      ip: String
  },
  data(){
      return {
          comments:[]
        
      }
  },
  components:{Comment}, 
  mounted (){
      
      axios.get('/comments',{params:{id:1,type:'post',}}).then((response)=>{
          
          this.comments=response.data
          
      })

      }
  }

</script>


 