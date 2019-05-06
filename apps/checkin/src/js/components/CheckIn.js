import React from 'react';
import {Notify, Notifier} from 'bc-react-notifier';
import CheckEmailNotFoundAC from './CheckEmailNotFoundAC';

export default class Checkin extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            showFormCheckin: false,
            showFormRegister: false,
            valueInput: [],
            idEvent: this.props.dataEvent[0].id,
            message: '',
            typeMessage: '',
            usersChecked: [],
            numberOfUsersInEvent: '',
            capacityEvent: '',
            disabledButton: false,
            //Form de nuevo registro
            first_name: '',
            last_name: '',
            email: '',
            status: '',
            error: null,
            statusBreathecode: ''
        };
    }

    componentDidMount(){
        this.getCapacityEvent();
        this.controlCapacityEvent();
    }

    controlCapacityEvent(){
        const endpoint = "https://assets-alesanchezr.c9users.io/apis/event/"+this.state.idEvent+"/checkin?access_token="+this.props.token;
        fetch(endpoint)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            const numbers = data.map((data, key)=>{
                return key;
            });
            this.setState({
                numberOfUsersInEvent: numbers.length
            });
        })
        .catch((error) => {
            console.log('error', error);
            this.setState({error});
        });
    }

    getCapacityEvent(){
        const endpoint = process.env.BREATHECODE+"all";
        fetch(endpoint)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            const capacityEvent = data.filter((c)=> c.id == this.state.idEvent).map((c, k)=>{
                return c.capacity;
            });

            this.setState({
                capacityEvent: capacityEvent[0]
            });
        })
        .catch((error) => {
            console.log('error', error);
            this.setState({error});
        });
    }

    //Mostrar y ocultar form para registrar usuarios a eventos
    showFormCheckin(){
        this.setState({
            showFormCheckin: !this.state.showFormCheckin
        });
    }

    //Se registra usuario en evento y se envia a ListChecked lista de asistentes actualizada
    checkinUserToEvent(event){
        const existingUsers = this.state.usersChecked.filter((c) => c.email == this.state.valueInput );
        event.preventDefault();
            const endpointCheckinEvent = process.env.BREATHECODE+this.state.idEvent+"/checkin?access_token="+this.props.token;
            const endpointBreathecode = process.env.BREATHECODE+"user/"+this.state.valueInput+"?access_token="+this.props.token;
            const endpointSearchBreathecode = process.env.BREATHECODE+'user/'+this.state.valueInput+'?access_token='+this.props.token;

            //Se desabilita el boton de checkin en la peticion al api
            this.setState({
                disabledButton: true
            });

            //checkin de active campaing
            fetch(endpointCheckinEvent, {
                headers: {"Content-Type": "application/json"},
                method: 'PUT',
                body: JSON.stringify({email: this.state.valueInput})
            })
            .then((response) => {
                //Se guardo
                if (response.status == 200){
                    this.setState({
                        status: '200',
                        disabledButton: false
                    });
                    return response.json();
                //Algo esta mal en el correo o ya se registro
                }else if(response.status == 400){
                    this.setState({
                        status: '400',
                        disabledButton: false
                    });
                    return response.json();
                //No existe el correo, no esta registrado en AC
                }else if(response.status == 401){
                    this.setState({
                        status: '401',
                        disabledButton: false
                    });
                    return response.json();
                }else if(response.status == 403){
                    this.setState({
                        status: '403',
                        disabledButton: false
                    });
                    return response.json();
                }else{
                    throw response;
                }
            })
            //Entra aqui si encuentra en ActiveCampaing y se guarda
            .then((data) => {
                if(this.state.status == 200){
                    if(this.state.numberOfUsersInEvent > this.state.capacityEvent) Notify.info('The user was successfully checked in but beware that the event is full');
                    else Notify.success('The user was successfully checked in');
                    this.setState({ valueInput: '' })
                    this.getAllUsersInEventUpdated();
                }else if(this.state.status == 400){
                    Notify.error(data.msg || data);
                    this.setState({
                        valueInput: ''
                    })
                }else if(this.state.status == 403){
                    Notify.error("Missing or invalid token");
                    this.setState({ valueInput: '' })
                }else if(this.state.status == 401){
                    //El usuario no esta registrado en AC, se va a consultar en breathecode
                    return fetch(endpointSearchBreathecode)
                    .then((response)=>{
                        if(response.status == 200){
                            this.setState({ statusBreathecode: '200' });
                            return response.json();
                        }else if(response.status == 404){
                            this.setState({ statusBreathecode: '404' });
                            return response.json();
                        }
                    })
                    //Se muestra el form de lo que se encontro
                    .then((data)=>{
                        if(this.state.statusBreathecode == 200){
                            this.setState({
                                disabledButton: false
                            });
                            Notify.info('This is what we found about this user');
                            this.setState({
                                first_name: data.full_name || data.first_name,
                                email: data.username,
                                showFormRegister: true,
                                showFormCheckin: false,
                            });
                        }else if(this.state.statusBreathecode == 404){
                            //Oculta el form de checkin al mostrar el modal de pregunta
                            this.setState({
                                showFormCheckin: false,
                                disabledButton: false
                            });

                            const ModalNotFind = ({ onConfirm }) => (
                                <div className="modal-not-found">
                                    <h3 className="text-center">The {this.state.valueInput} email was not found, do you want to register a new one?</h3>
                                    <div className="center-btn-modal">
                                        <button type="button" className="btn btn-outline-danger text-center mt-2" onClick={()=>onConfirm(false)}>No, back to the search</button>
                                        <button type="button" className="btn btn-outline-primary text-center mt-2" onClick={()=>onConfirm(true)}>Yes, register user</button>
                                    </div>
                                </div>
                            );

                            let noti = Notify.add('info', ModalNotFind, (answer)=>{
                                if(answer){
                                    this.setState({
                                        first_name: '',
                                        email: '',
                                        showFormRegister: true,
                                    });
                                }else{
                                    this.setState({
                                        showFormRegister: false,
                                        showFormCheckin: true,
                                        valueInput: ''
                                    });
                                }
                                noti.remove();
                            }, 9999999999999);
                        }
                    });
                }
            })
            //No esta breathecode tampoco
            .catch((error)=>{
                console.log('error', error);
                this.setState({error});
            });
    }

    getAllUsersInEventUpdated(){
        const endpointGetAllUsersInEvent = process.env.BREATHECODE+this.state.idEvent+"/checkin?access_token="+this.props.token;

            fetch(endpointGetAllUsersInEvent)
            .then((response) => {
                return response.json()
            })
            .then((data)=>{
                //Ordenar de mayor a menor
                data.sort((a, b) => {
                    return b.id - a.id;
                });
                //Actualiza lista de users
                this.props.getUsersInEvent(data);
            })
    }

    handleChange(event){
        this.setState({
            valueInput: event.target.value
        })
    }

    hiddenFormRegister(){
        this.setState({
            showFormRegister: false,
            email: '',
            first_name: '',
            last_name: ''
        })
    }

    render(){
        return(
            <div className="container-fluid p-0">
                <div className="row justify-content-md-center alert-primary full-width no-margin">
                    <div className="col-12 no-padding">
                        <nav className="navbar navbar-dark bg-dark">
                            <a className="navbar-brand" href="#">
                                Attendees
                            </a>
                            <div className="ml-auto">
                                <button
                                    disabled={this.state.disabledButton}
                                    type="button"
                                    className="btn btn-primary"
                                    onClick={()=>this.showFormCheckin()}>Checkin User</button>
                            </div>
                        </nav>
                    </div>

                    <div className="col-md-12 no-padding">
                        <Notifier/>
                    </div>

                    {/*Formulario de checkin*/}
                    {(this.state.showFormCheckin) ?
                    <div className="col-md-8 pt-5 pb-5">
                        <form onSubmit={(event)=>this.checkinUserToEvent(event)}>
                            <div className="form-group row">
                                <div className="col-sm-10">
                                <input
                                    type="text"
                                    className="form-control mt-3"
                                    placeholder="Specify attende email"
                                    value={this.state.valueInput}
                                    onChange={(event)=> this.handleChange(event)}
                                    />
                                </div>
                                {(this.state.disabledButton) ?
                                    <button type="submit" disabled={this.state.disabledButton} className="btn btn-primary ml-3 mt-3">Loading</button>
                                :
                                    <button type="submit" className="btn btn-primary ml-3 mt-3">Check In</button>
                                }
                            </div>
                        </form>
                    </div> : ''}

                    {(this.state.showFormRegister) ?
                        <CheckEmailNotFoundAC
                            idEvent={this.state.idEvent}
                            email={this.state.valueInput}
                            token={this.props.token}
                            first_name={this.state.first_name}
                            capacityEvent={this.state.capacityEvent}
                            numberOfUsersInEvent={this.state.numberOfUsersInEvent}
                            hiddenFormRegister={()=>this.hiddenFormRegister()}
                            showListUsersInEvent={()=>this.getAllUsersInEventUpdated()}/>
                     : ''}
                </div>
            </div>
        )
    }
}