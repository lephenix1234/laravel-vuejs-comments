import Vue from 'Vue'
import Comments from './components/Comments.vue'

// eslint-disable-next-line
new Vue({
  el: '#app',
  components: { Comments },
  render: h => h(Comments)

}).$mount()
