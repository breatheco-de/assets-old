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
        }
    }

    componentDidMount(){
        this.getAllUsersInEvent();
    }

    //Se muestra la lista de asistentes al ingresar al detalle del evento
    getAllUsersInEvent(){
        const endpoint = process.env.BREATHECODE+this.state.idEvent+"/checkin";
        fetch(endpoint)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            console.log(data);
            if(data.length > 0){
                //Ordenar de mayor a menor
                data.sort((a, b) => {
                    return b.id - a.id;
                });
                this.setState({
                    usersChecked: data
                })
                this.setState({
                    listUsersInEvent: data,
                    showList: true
                })
            }else{
                this.setState({
                    showList: false
                })

            }
        })
        .catch((error) => {
            console.log('error', error);
        })
    }

    // controlCapacityEvent(){
    //     const endpoint = "https://assets-alesanchezr.c9users.io/apis/event/"+this.state.idEvent+"/checkin";
    //     fetch(endpoint)
    //     .then((response) => {
    //         return response.json();
    //     })
    //     .then((data) => {
    //         const numbers = data.map((data, key)=>{
    //             return key
    //         })
    //         this.setState({
    //             numberOfUsers: numbers
    //         })
    //         console.log(this.state.numberOfUsers);
    //     })
    //     .catch((error) => {
    //         console.log('error', error);
    //     })
    // }

    // getCapacityEvent(){
    //     const endpoint = process.env.BREATHECODE+"all";
    //     fetch(endpoint)
    //     .then((response) => {
    //         return response.json();
    //     })
    //     .then((data) => {
    //         const capacityEvent = data.filter((c)=> c.id == this.state.idEvent).map((c, k)=>{
    //             return c.capacity
    //         })

    //         this.setState({
    //             capacityEvent
    //         })
    //     })
    //     .catch((error) => {
    //         console.log('error', error);
    //     })
    // }

    usersInEvent(data){
        (data.length > 0) ? 
        this.setState({
            listUsersInEvent: data,
            showList: true
        }) :
        this.setState({
            listUsersInEvent: data,
            showList: false
        })
    }

    render(){
        return(
            <div>
                <Header backToHome={true}/>
                <RowText title="Hola" />
                <DetailEvent dataEvent={this.props.data}/>
                <CheckIn 
                    dataEvent={this.props.data} 
                    idEvent={this.props.data[0].id}
                    getUsersInEvent={(data)=>this.usersInEvent(data)}/>
                <ListChecked 
                    data={this.state.listUsersInEvent} 
                    showList={this.state.showList}/>
            </div>
        )
    }
}