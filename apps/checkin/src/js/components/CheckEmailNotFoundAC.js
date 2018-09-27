import React from 'react';
import {Notify, Notifier} from '@breathecode/react-notifier';
import Empty from './message/Empty';
import Success from './message/Success';
import Warning from './message/warning';

export default class CheckEmailNotFoundAC extends React.Component{
    constructor(props){
        super(props);
        this.state={
            idEvent: '',
            email: '',
            first_name: '',
            last_name: '',
            invalidClassEmail: '',
            invalidClassFirst: '',
            invalidClassLast: '',
            showFormRegister: false,
            disabledButton: false
        }
    }

    static getDerivedStateFromProps(nextProps, prevState) {
        if (nextProps.email !== prevState.email && nextProps.first_name !== prevState.first_name && nextProps.idEvent !== prevState.idEvent) {
            return {
                email: nextProps.email,
                first_name: nextProps.first_name,
                idEvent: nextProps.idEvent
            }
        }else {
            return {
                idEvent: nextProps.idEvent
            }
        }
        return null;
    }

    checkinNewUserToEvent(event){
        event.preventDefault();

        //Se desabilita el boton de checkin en la peticion al api
        this.setState({
            disabledButton: true
        })

        const endpointRegisterUserAC = process.env.ADD_USER_AC+"?access_token="+process.env.TOKEN;
        const endpointCheckinEvent = process.env.ACTIVECAMPAING+this.state.idEvent+"/checkin?access_token="+process.env.TOKEN;

        //Si los input en el form estan llenos, se hace el POST
        if(this.state.email.length != 0 && this.state.first_name.length != 0 && this.state.last_name.length != 0){
            //Se guarda en ActiveCampaing con los datos provenientes de Breathecode
            fetch(endpointRegisterUserAC, {
                headers: {"Content-Type": "application/json"},
                method: 'POST',
                body: JSON.stringify({
                    email: this.state.email,
                    first_name: this.state.first_name,
                    last_name: this.state.last_name
                })
            })
            .then((response)=>{
                if (response.status == 200){
                    return response.json();
                }else{
                    throw response;
                }
            })
            .then((data)=>{
                //Se chekea el usuario en el evento previamente creado en ActiveCampaing
                return fetch(endpointCheckinEvent, {
                                    headers: {"Content-Type": "application/json"},
                                    method: 'PUT',
                                    body: JSON.stringify({email: this.state.email})
                                })
                .then((response)=>{
                    if (response.status == 200){
                        this.setState({
                            status: '200',
                            disabledButton: false
                        })
                        return response.json();
                    }else{
                        throw response;
                    }
                })
                .then((data)=>{
                    if(this.props.numberOfUsersInEvent > this.props.capacityEvent){
                        let noti = Notify.add('info', Warning, ()=>{
                            noti.remove();
                        }, 3000);    
                    }
                    let noti = Notify.add('info', Success, ()=>{
                        noti.remove();
                    }, 3000);
                    this.props.hiddenFormRegister();
                    this.props.showListUsersInEvent();
                })
                .catch((error)=>{
                    //No chekeao el usuario creado en el evento
                    this.setState({
                        disabledButton: false
                    })
                })
            })
            .catch((error)=>{
                //No registro el usuario en activeCampaing
                this.setState({
                    disabledButton: false
                })
                console.log('error', error);
            })

        //Se condiciona si el campo esta vacio para mostrar rojo el input
        }else if(this.state.email.length == 0){
            this.setState({
                invalidClassEmail:'is-invalid'
            })
            let noti = Notify.add('info', Empty, ()=>{
                noti.remove();
            }, 3000);
        }else if(this.state.first_name.length == 0){
            this.setState({
                invalidClassFirst:'is-invalid'
            });
            let noti = Notify.add('info', Empty, ()=>{
                noti.remove();
            }, 3000);
        }else if(this.state.last_name.length == 0){
            this.setState({
                invalidClassLast:'is-invalid'
            })
            let noti = Notify.add('info', Empty, ()=>{
                noti.remove();
            }, 3000);
        }
    }

    handleChangeInputEmail(event){
        (event.target.value.length > 0) ?
            this.setState({
                email: event.target.value,
                invalidClassEmail: ''
            })
        : this.setState({
            email: event.target.value,
            invalidClassEmail: 'is-invalid'
        })
    }

    handleChangeInputFirstName(event){
        (event.target.value.length > 0) ?
            this.setState({
                first_name: event.target.value,
                invalidClassFirst: ''
            })
        : this.setState({
            first_name: event.target.value,
            invalidClassFirst: 'is-invalid'
        });
    }

    handleChangeInputLastName(event){
        (event.target.value.length > 0) ?
            this.setState({
                last_name: event.target.value,
                invalidClassLast: ''
            })
        : this.setState({
            last_name: event.target.value,
            invalidClassLast: 'is-invalid'
        })
    }

    cancelForm(){
        this.props.hiddenFormRegister();
    }

    render(){
            return (
                <div className="full-width">
                <div className="row justify-content-center full-width no-margin">
                    <div className="col-md-8 col-sm-10 col-11 pt-5 pb-5">
                        <form className="form" onSubmit={(event)=>this.checkinNewUserToEvent(event)}>
                        <div className="form-group row">
                            <label className="col-sm-2 col-form-label text-black">Email</label>
                            <div className="col-sm-10">
                            <input 
                                type="text" 
                                className={'form-control '+this.state.invalidClassEmail}
                                placeholder="Email"
                                value={this.state.email}
                                onChange={(event)=>this.handleChangeInputEmail(event)}
                                />
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-sm-2 col-form-label text-black">First Name</label>
                            <div className="col-sm-10">
                            <input 
                                type="text" 
                                className={'form-control '+this.state.invalidClassFirst}
                                placeholder="First Name"
                                value={this.state.first_name}
                                onChange={(event)=>this.handleChangeInputFirstName(event)}
                                />
                            </div>
                        </div>
                        <div className="form-group row">
                            <label className="col-sm-2 col-form-label text-black">Last Name</label>
                            <div className="col-sm-10">
                            <input 
                                type="text" 
                                className={'form-control '+this.state.invalidClassLast}
                                placeholder="Last Name"
                                value={this.state.last_name}
                                onChange={(event)=>this.handleChangeInputLastName(event)}
                                />
                            </div>
                        </div>
                            
                        <div className="float-right">
                        {(this.state.disabledButton) ?
                            <div>
                                <button type="button" disabled={this.state.disabledButton} className="btn btn-outline-secondary ml-3" onClick={()=>this.cancelForm()}>Cancel</button>
                                <button type="submit" disabled={this.state.disabledButton} className="btn btn-outline-success ml-3">Loading</button>
                            </div>
                            :
                            <div>
                                <button type="button" className="btn btn-outline-secondary ml-3" onClick={()=>this.cancelForm()}>Cancel</button>
                                <button type="submit" className="btn btn-outline-success ml-3">Save and Check In</button>
                            </div>
                        }
                        </div>
                        </form>
                    </div>
                </div>
                </div>
            )
    }
}