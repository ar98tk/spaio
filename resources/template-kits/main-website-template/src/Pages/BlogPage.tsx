import Blog5 from "../Components/Blog/Blog5";
import BreadCumb from "../Components/Common/BreadCumb";

const BlogPage = () => {
    return (
        <div>
              <BreadCumb
                bgimg="/assets/img/page-heading-bg.svg"
                Title="Blog"
            ></BreadCumb>    
            <Blog5></Blog5>          
        </div>
    );
};

export default BlogPage;