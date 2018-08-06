import React from 'react';
import NewBadges from './NewBadges';

export default class Info extends React.Component {
	constructor(props){
        super(props);
		this.state = {
            nameInfo: '',
            mainInfo: '',
            resultInfo: '',
            badges: [],
            slug: ''
        }
    }

    componentWillReceiveProps(props){
        this.setState({
            nameInfo: props.data.name,
            mainInfo: props.data.main,
            resultInfo: props.data.results,
            slug: props.data.slug,
            badges: props.data.badges
        })
    }
    
    handleChangeName(event){
		this.setState({
			nameInfo: event.target.value
        });

        this.props.handleJsonInfo({
            name: event.target.value,
            main: this.state.mainInfo,
            results: this.state.resultInfo,
            badges: this.state.badges,
            slug: this.state.slug
        }, {typeRequest: 'name'});
    }
    
    handleChangeMain(event){
		this.setState({
			mainInfo: event.target.value
        });

        this.props.handleJsonInfo({
            name: this.state.nameInfo,
            main: event.target.value,
            results: this.state.resultInfo,
            badges: this.state.badges,
            slug: this.state.slug
        }, {typeRequest: 'nameMain'});
    }

    handleChangeResult(event){
		this.setState({
			resultInfo: event.target.value
        });

        this.props.handleJsonInfo({
            name: this.state.nameInfo,
            main: this.state.mainInfo,
            results: event.target.value,
            badges: this.state.badges,
            slug: this.state.slug
        }, {typeRequest: 'nameResult'});
    }

    handleChangeSlug(event, value, key){
        this.setState({
            slug: event.target.value
        });

        this.props.handleJsonInfo({
            name: this.state.nameInfo,
            main: this.state.mainInfo,
            results: this.state.resultInfo,
            badges: this.state.badges,
            slug: event.target.value
        }, {typeRequest: 'nameSlug'});
    }

    handleChangeNameBadges(event, value, key){
        console.log(event, value, key);
        let resultBadges = this.state.badges.map((data, id)=>{
            if(key == id){
                return ({slug: event.target.value, points: data.points })
            }else{
                return({slug: data.slug, points: data.points})
            }
        });
        this.setState({
            badges: resultBadges
        });

        this.props.handleJsonInfo({
            name: this.state.nameInfo,
            main: this.state.mainInfo,
            results: this.state.resultInfo,
            badges: resultBadges,
            slug: this.state.slug
        }, {typeRequest: 'nameBadges'});
    }

    handleChangePointBadges(event, value, key){
        console.log(event, value, key);
        let resultBadges = this.state.badges.map((data, id)=>{
            if(key == id){
                return ({slug: data.slug, points: event.target.value })
            }else{
                return({slug: data.slug, points: data.points})
            }
        });
        this.setState({
            badges: resultBadges
        });

        this.props.handleJsonInfo({
            name: this.state.nameInfo,
            main: this.state.mainInfo,
            results: this.state.resultInfo,
            badges: resultBadges,
            slug: this.state.slug
        }, {typeRequest: 'pointBadges'});
    }

    handleNewBadges(){
		const newBadges = {
			slug: '',
			points: ''
		}
		this.setState({
			badges: [...this.state.badges, newBadges]
		});
		
    }

    handleDeleteBadges(data, i){
		let filteredBadges = this.state.badges.filter(el => el != data );
		this.setState(({
			badges: filteredBadges
		}));
	}
	render () {
        let resultBadges = null;
        resultBadges = this.state.badges.map((value, key) =>(
            <div className="form-row" key={key}>
				<div className="form-group col-md-4">
                    <input
                        type="text"
                        className="form-control"
                        value={value.slug}
                        onChange={(event)=>this.handleChangeNameBadges(event, value, key)}
                        placeholder="Slug"
                    />
				</div>
                <div className="form-group col-md-4">
                    <input
                        type="number"
                        className="form-control"
                        value={value.points}
                        onChange={(event)=>this.handleChangePointBadges(event, value, key)}
                        placeholder="Points"
                    />
                </div>
                    <button 
                        type="button"
                        onClick={() => this.handleDeleteBadges(value, key)}
                        className="btn text-danger float-right">delete
                    </button>
            </div>



                    /*<div className="form-group" key={key}>
                        <label>Badges</label>
                        <input
                            type="text"
                            className="form-control"
                            value={value.slug}
                            onChange={(event)=>this.handleChangeNameBadges(event, value, key)}
                        />
                        <label>Points</label>
                        <input
                            type="number"
                            className="form-control"
                            value={value.points}
                            onChange={(event)=>this.handleChangePointBadges(event, value, key)}
                        />
                    </div>*/
        ));
		return (
            <div>
            <div className="section-question p-4">
                <div className="form-group">
                    <label>Quiz title</label>
                    <input 
                        type="text"
                        className="form-control"
                        value={this.state.nameInfo}
                        onChange={this.handleChangeName.bind(this)}
                    />
                </div>
                    <div className="form-group">
                        <label>Description shown to the student before starting the quiz</label>
                        <input
                            type="text"
                            className="form-control"
                            value={this.state.mainInfo}
                            onChange={this.handleChangeMain.bind(this)}
                        />
                    </div>
                    <div className="form-group">
                        <label>Last message once the quiz is over</label>
                        <input
                            type="text"
                            className="form-control"
                            value={this.state.resultInfo}
                            onChange={this.handleChangeResult.bind(this)}
                        />
                    </div>
                    <div className="form-group">
                        <label>Slug</label>
                        <input
                            type="text"
                            className="form-control"
                            value={this.state.slug}
                            onChange={this.handleChangeSlug.bind(this)}
                        />
                    </div>
            </div>
            <div className="row">
                <div className="col-12">
                    <nav className="questions-nav navbar sticky-top navbar-dark bg-dark">
                        <a className="navbar-brand" href="#">
                            Badges
                        </a>
                        <div className="ml-auto">
    					    <NewBadges onClick={this.handleNewBadges.bind(this)}/>
    				    </div>
                    </nav>
                </div>
                <div className="col-12 col-sm-10 col-md-8 col-xl-6 mx-auto badges">
                    {resultBadges}
                </div>
            </div>
            </div>
		);
	}
}