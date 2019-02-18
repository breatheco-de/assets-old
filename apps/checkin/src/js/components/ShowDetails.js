import React from 'react';

import Header from './Header';
import RowText from './RowText';
import DetailEvent from './DetailEvent';
import CheckIn from './CheckIn';
import ListChecked from './ListChecked';

export default class ShowDetails extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            idEvent: this.props.data[0].id,
            listUsersInEvent: [],
            showList: false,
            capacityEvent: ''
        };
    }

    componentDidMount(){
        this.getAllUsersInEvent();
    }

    //Se muestra la lista de asistentes al ingresar al detalle del evento
    getAllUsersInEvent(){
        const endpoint = process.env.BREATHECODE+this.state.idEvent+"/checkin?access_token="+this.props.token;
        fetch(endpoint)
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if(data.length > 0){
                    //Ordenar de mayor a menor
                    data.sort((a, b) => {
                        return b.id - a.id;
                    });
                    this.setState({
                        usersChecked: data
                    });
                    this.setState({
                        listUsersInEvent: data,
                        showList: true
                    });
                }else{
                    this.setState({
                        showList: false
                    });
    
                }
            })
            .catch((error) => {
                console.log('error', error);
            });
    }

    usersInEvent(data){
        (data.length > 0) ? 
        this.setState({
            listUsersInEvent: data,
            showList: true
        }) :
        this.setState({
            listUsersInEvent: data,
            showList: false
        });
    }

    render(){
        return(
            <div>
                <Header backToHome={true} btnSeeOldEvents={false}/>
                <RowText />
                <DetailEvent dataEvent={this.props.data}/>
                <CheckIn 
                    dataEvent={this.props.data} 
                    idEvent={this.props.data[0].id}
                    getUsersInEvent={(data)=>this.usersInEvent(data)}
                    token={this.props.token}/>
                <ListChecked 
                    data={this.state.listUsersInEvent} 
                    showList={this.state.showList}/>
            </div>
        );
    }
}