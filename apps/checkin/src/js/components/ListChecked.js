import React from 'react';

export default class ListChecked extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            cantUsersList: 5,
            hiddenReadMore: false
        }
    }

    readMore(){
        console.log('aqui')
        this.setState({
            cantUsersList: this.state.cantUsersList+5
        })
    }

    render(){
        const tableUsers = this.props.data.map((data, key)=>{
            if(key <= this.state.cantUsersList){
                return (
                        <tr key={key}>
                            <td>{key}</td>
                            <td>{data.id}</td>
                            <td>{data.email}</td>
                        </tr>
                )
            }
        })

        return(
            <div>
                {(this.props.showList) ?     
                    <div className="row justify-content-center background-form pt-5 pb-5 full-width no-margin">
                        <div className="col-md-8">
                            <table className="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">ID User</th>
                                        <th scope="col">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {tableUsers}
                                </tbody>
                            </table>
                            <p onClick={()=>this.readMore()} className="read-more-list">Read More</p>
                        </div>
                    </div>
                    :
                    <div className="row background-form pt-5 pb-5">
                        <div className="col-md-12">
                            <p className="text-center">There is no one on the list</p>
                        </div>
                    </div>
                }
            </div>
        )
    }
}