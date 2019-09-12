import React from 'react';
import Header from './components/Header';
import SelectEvents from './components/SelectEvents';
import ShowDetails from './components/ShowDetails';
import {Notify, Notifier} from 'bc-react-notifier';
import { updateLocale } from 'moment';

const ModalComponent = ({ onConfirm }) =>
        <div>
            <h1>Are you sure?</h1>
            <button onClick={()=>onConfirm(true)}>Yes</button>
            <button onClick={()=>onConfirm(false)}>No</button>
        </div>;

export default class App extends React.Component {

  constructor (props) {
    super(props);
    this.state = {
      showSelect: true,
      dataEventsToDay: [],
      tokenUrl: '',
      dataOldEvents: [],
	  showOldEvents: false,
	  showUsersInEventsOld: false
    };
  }

  componentDidMount(){
    //Se toma el token por url para enviarse a los diferentes component que lo necesitan
    var url = new URL(window.location.href);
    var tokenUrl = url.searchParams.get("bc_token");
    if(tokenUrl !== null) this.setState({tokenUrl});
    else Notify.error('Missing API token');
  }

  dataForForm(data){
    this.setState({
      showSelect: false,
      dataEventsToDay: data
    });
  }

  oldEventsFromHeader(data){
    if(data){
      this.setState({
        showOldEvents: !this.state.showOldEvents,
		dataOldEvents: data,
		usersInOldEvent: [],
		idEventOld: ''
	  })
    }
  }

	usersByIdEvent(idEvent){
		var url = new URL(window.location.href);
		var tokenUrl = url.searchParams.get("access_token");

		const endpoint = process.env.BREATHECODE+"/"+idEvent+'/checkin?access_token='+tokenUrl;
		fetch(endpoint)
		.then((response)=>{
			return response.json();
		})
		.then((data)=>{
			if(data.length > 0){
				this.setState({
					usersInOldEvent: data,
					idEventOld: idEvent,
					showUsersInEventsOld: !this.state.showUsersInEventsOld
				})
			}else{
				Notify.error('This event is not have users');
				this.setState({
					showUsersInEventsOld: !this.state.showUsersInEventsOld
				})
			}
		})
		.catch((error)=>{
			console.log(error);
		})
	}

	showUsersInEventsOld(idEvent){
		this.usersByIdEvent(idEvent);
	}

  render () {
    const listOldEvents = this.state.dataOldEvents.map((data, key) => {
      return(
        <div className="row full-width border no-margin" key={key} onClick={()=>this.showUsersInEventsOld(data.id)}>
			<div className="col-12 header-oldevents">
                <p className="no-margin date">{data.event_date}</p>
				<p className="no-margin title">{data.title}</p>
				<p className="no-margin text-title">{data.description.substring(0,120)}</p>
			</div>
			{(this.state.usersInOldEvent && this.state.showUsersInEventsOld && this.state.idEventOld == data.id) ?
				this.state.usersInOldEvent.map((eventOld, i)=>{
					return (
						<div className="col-12" key={i}>
							<p>{i+1}) {eventOld.email}</p>
						</div>
					)
				}) : ''}
		</div>
      )
    })
    return (
      (this.state.showSelect && this.state.tokenUrl !== null) ?
        <div>
            <Header sendOldEventsToApp={(data)=>this.oldEventsFromHeader(data)} btnSeeOldEvents={true}/>
            <Notifier />
            <SelectEvents
              label="Select the event you are working at:"
              placeholder="Select an event"
              receiveData={(data)=> this.dataForForm(data)}/>
            {
              (this.state.showOldEvents && this.state.dataOldEvents) ?
				<div className="container">
					{listOldEvents}
				</div>
              : ''
            }
        </div>
        :
        <div>
          <ShowDetails data={this.state.dataEventsToDay} token={this.state.tokenUrl}/>
        </div>
    );
  }
}