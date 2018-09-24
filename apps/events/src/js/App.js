import React from 'react';
import Header from './components/Header';
import SelectEvents from './components/SelectEvents';
import ShowDetails from './components/ShowDetails';

export default class App extends React.Component {

  constructor (props) {
    super(props)
    this.state = {
      showSelect: true,
      dataEventsToDay: []
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
      (this.state.showSelect) ?
        <div>
            <Header />
            <SelectEvents 
              label="Select the event you are working at:" 
              placeholder="Events" 
              receiveData={(data)=> this.dataForForm(data)}/>
        </div>
        :
        <div>
          <ShowDetails data={this.state.dataEventsToDay}/>
        </div>
    )
  }
}