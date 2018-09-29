import React from 'react';
import Header from './components/Header';
import SelectEvents from './components/SelectEvents';
import ShowDetails from './components/ShowDetails';
import {Notify, Notifier} from '@breathecode/react-notifier';
import MissingToken from './components/message/MissingToken';

export default class App extends React.Component {

  constructor (props) {
    super(props)
    this.state = {
      showSelect: true,
      dataEventsToDay: [],
      tokenUrl: ''
    }
  }

  componentDidMount(){
    //Se toma el token por url para enviarse a los diferentes component que lo necesitan
    var url = new URL(window.location.href);
    var tokenUrl = url.searchParams.get("assets_token");
    if(tokenUrl !== null){
      this.setState({tokenUrl});
    }else{
      let noti = Notify.add('info', MissingToken, ()=>{
        noti.remove();
      }, 5000);
    }
  }

  dataForForm(data){
    this.setState({
      showSelect: false,
      dataEventsToDay: data
    })
  }

  render () {
    return (
      (this.state.showSelect && this.state.tokenUrl !== null) ?
        <div>
            <Header />
            <Notifier />
            <SelectEvents 
              label="Select the event you are working at:" 
              placeholder="Events" 
              receiveData={(data)=> this.dataForForm(data)}/>
        </div>
        :
        <div>
          <ShowDetails data={this.state.dataEventsToDay} token={this.state.tokenUrl}/>
        </div>
    )
  }
}