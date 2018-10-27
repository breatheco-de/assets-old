import React from 'react';

export default class GetQuiz extends React.Component {
	constructor(){
        super();
        
        this.state = {
            slugQuiz: '',
            allQuiz: []
        }
    }

    handleGetQuiz(){
        fetch('https://assets.breatheco.de/apis/quiz/all')
		.then(function(response) {
			return response.json();
		})
		.then((data) => {
            this.setState({
                allQuiz: data
            })
		})
		.catch(function(error){
			console.log('error', error);
		})
    }

    componentDidMount(){
        this.handleGetQuiz();
    }
    
    handleSubmit(event){
        event.preventDefault();
        console.log(this.state.slugQuiz);
        this.props.onSelect(this.state.slugQuiz);
    }

    handleChange(event){
        console.log(event.target.value)
        this.setState({
            slugQuiz: event.target.value
        });
    }

	render () {
        const styleOption = {
            width: '100%',
            height: '100%'
        };
        let slugQuiz = Object.entries(this.state.allQuiz);
        let optionSelect = slugQuiz.map((value, key)=>(
            (value[1].info.slug) ? <option key={key} value={value[1].info.slug} onChange={(event)=>this.handleChange(event)}>{value[1].info.slug}</option> : ''
        ))
		return (
            <div className="alert alert-primary">
                <h4 className="alert-heading">Load Quiz from URL</h4>
                <form onSubmit={(event)=>this.handleSubmit(event)}>
                  <div className="form-row">
                    <div className="form-group col-md-8">
                        <select style={styleOption} onChange={(event)=>this.handleChange(event)}>
                            <option defaultValue>All Quiz</option>
                            {optionSelect}
                        </select>
                    </div>
                    <div className="form-group col">
                        <button type="submit" className="btn btn-light form-control">Load Quiz</button>
                    </div>
                  </div>
                </form>
            </div>
		);
	}
}